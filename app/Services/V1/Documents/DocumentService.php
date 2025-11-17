<?php

namespace App\Services\V1\Documents;

use App\Exceptions\DocumentNotFoundException;
use App\Exceptions\DocumentAccessDeniedException;
use App\Exceptions\BadRequestException;
use App\Models\Documents;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Crypt;

class DocumentService
{
    protected $currentDocument;

    // Allowed MIME types for preview
    protected $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/octet-stream', // Temporary for debugging
        'text/plain',
    ];

    protected $maxFileSize = 10485760; // 10MB

    /**
     * Validate document access and return the document (for direct calls)
     */
    public function getDocumentPreview($documentId)
    {
        // Validate access and get the document
        $document = $this->validateAndGetDocument($documentId);
        $filePath = $this->getFilePathFromDocument($document);

        $this->validateFileExists($filePath);

        // Check if file is encrypted
        $isEncrypted = $this->isFileEncrypted($filePath);

        if ($isEncrypted) {
            return $this->handleEncryptedFilePreview($filePath, $document);
        } else {
            return $this->handleRegularFilePreview($filePath, $document);
        }
    }

    /**
     * Validate document access for fluent interface
     */
    public function validateDocumentAccess($documentId)
    {
        $this->currentDocument = $this->validateAndGetDocument($documentId);
        return $this;
    }

    /**
     * Validate document access and return the document
     */
    private function validateAndGetDocument($documentId)
    {
        $document = Documents::find($documentId);

        if (!$document) {
            throw new DocumentNotFoundException('Document not found');
        }

        $user = auth()->user();
        if (!$this->userCanAccessDocument($user, $document)) {
            Log::warning('Document access denied', [
                'user_id' => $user->id,
                'document_id' => $documentId,
                'user_role' => $user->getRoleNames()->first()
            ]);
            throw new DocumentAccessDeniedException('Access denied to this document');
        }

        return $document;
    }

    /**
     * Check if user can access the document based on role
     */
    private function userCanAccessDocument($user, $document)
    {
        // Super-admin and admin can access all documents
        if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
            return true;
        }

        // Applicant can only access their own documents
        if ($user->hasRole('applicant')) {
            return $this->isDocumentOwner($user, $document);
        }

        return false;
    }

    /**
     * Check if applicant owns this document
     */
    private function isDocumentOwner($user, $document)
    {
        // Check if document belongs to user's request/application
        if ($document->entityId) {
            $request = \App\Models\Requests::find($document->entityId);
            if ($request && $request->user_id === $user->id) {
                return true;
            }
        }

        // Alternative: Check if document has direct user relationship
        if (isset($document->user_id) && $document->user_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Extract file path from document meta or documentName
     */
    private function getFilePathFromDocument($document)
    {
        \Log::info('Getting file path for document:', [
            'document_id' => $document->id,
            'document_name' => $document->documentName,
            'meta' => $document->meta
        ]);

        // Files are stored in public disk
        $storageDisk = 'public';
        $basePath = 'requests/documents/';

        // First check if path is stored in meta
        if ($document->meta && is_array($document->meta)) {
            if (isset($document->meta['file_path'])) {
                $filePath = $document->meta['file_path'];
                \Log::info('Found file path in meta:', ['file_path' => $filePath]);
                return $filePath;
            }
        }

        // Construct the file path
        $filePath = $basePath . $document->documentName;

        \Log::info('Checking file path:', [
            'disk' => $storageDisk,
            'file_path' => $filePath,
            'exists' => Storage::disk($storageDisk)->exists($filePath)
        ]);

        if (!Storage::disk($storageDisk)->exists($filePath)) {
            \Log::error('Document file not found:', [
                'disk' => $storageDisk,
                'file_path' => $filePath,
                'available_files' => Storage::disk($storageDisk)->files($basePath)
            ]);
            throw new DocumentNotFoundException('Document file not found in storage. Path: ' . $filePath);
        }

        return $filePath;
    }

    /**
     * Check if file is encrypted (has .enc extension)
     */
    private function isFileEncrypted($filePath)
    {
        return str_ends_with($filePath, '.enc');
    }

    /**
     * Handle preview for encrypted files
     */
    private function handleEncryptedFilePreview($filePath, $document)
    {
        try {
            \Log::info('Starting encrypted file preview:', [
                'file_path' => $filePath,
                'document_id' => $document->id
            ]);

            // Read and decrypt the file from public disk
            $encryptedContent = Storage::disk('public')->get($filePath);

            \Log::info('Encrypted content read:', [
                'content_size' => strlen($encryptedContent),
                'file_path' => $filePath
            ]);

            $decryptedContent = Crypt::decrypt($encryptedContent);

            \Log::info('File decrypted successfully:', [
                'decrypted_size' => strlen($decryptedContent)
            ]);

            // Get file size from decrypted content
            $fileSize = strlen($decryptedContent);

            // Validate file size
            if ($fileSize > $this->maxFileSize) {
                throw new BadRequestException('File too large for preview. Maximum size is 10MB.');
            }

            // Determine MIME type from document meta
            $mimeType = $this->getMimeTypeFromDocument($document);
            \Log::info('MIME type determined:', ['mime_type' => $mimeType]);

            $this->validateMimeType($mimeType, $document->id, $document->documentName);

            $headers = $this->getSecurityHeaders($mimeType, $this->getOriginalFileName($document), $fileSize);

            $this->logDocumentAccess($document);

            \Log::info('Returning successful preview response');

            // Create response with decrypted content
            return new StreamedResponse(function () use ($decryptedContent) {
                echo $decryptedContent;
            }, 200, $headers);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            \Log::error('Decryption failed:', [
                'error' => $e->getMessage(),
                'file_path' => $filePath,
                'document_id' => $document->id
            ]);
            throw new BadRequestException('Unable to decrypt document: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Encrypted file preview error:', [
                'error' => $e->getMessage(),
                'file_path' => $filePath,
                'document_id' => $document->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw new BadRequestException('Unable to preview document: ' . $e->getMessage());
        }
    }

    /**
     * Handle preview for regular (non-encrypted) files
     */
    private function handleRegularFilePreview($filePath, $document)
    {
        // Use public disk for file operations
        $fileSize = Storage::disk('public')->size($filePath);
        if ($fileSize > $this->maxFileSize) {
            throw new BadRequestException('File too large for preview. Maximum size is 10MB.');
        }

        $mimeType = Storage::disk('public')->mimeType($filePath);
        $this->validateMimeType($mimeType, $document->id, $document->documentName);

        $headers = $this->getSecurityHeaders($mimeType, $document->documentName, $fileSize);

        $this->logDocumentAccess($document);

        return $this->createSecureStreamedResponse($filePath, $headers);
    }

    /**
     * Validate file exists in storage
     */
    private function validateFileExists($filePath)
    {
        if (!Storage::disk('public')->exists($filePath)) {
            throw new DocumentNotFoundException('Document file not found in storage: ' . $filePath);
        }
    }

    /**
     * Get MIME type from document meta or file extension
     */
    private function getMimeTypeFromDocument($document)
    {
        \Log::info('Getting MIME type for document:', [
            'document_id' => $document->id,
            'meta' => $document->meta,
            'document_name' => $document->documentName
        ]);

        // Try to get from meta first
        if ($document->meta) {
            $metaArray = is_array($document->meta) ? $document->meta : json_decode($document->meta, true);

            if (isset($metaArray['extension'])) {
                $extension = strtolower($metaArray['extension']);
                $mimeMap = [
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                    'webp' => 'image/webp',
                    'svg' => 'image/svg+xml',
                    'pdf' => 'application/pdf',
                    'doc' => 'application/msword',
                    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'xls' => 'application/vnd.ms-excel',
                    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'ppt' => 'application/vnd.ms-powerpoint',
                    'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'txt' => 'text/plain',
                ];

                if (isset($mimeMap[$extension])) {
                    $mimeType = $mimeMap[$extension];
                    \Log::info('MIME type determined from extension:', [
                        'extension' => $extension,
                        'mime_type' => $mimeType
                    ]);
                    return $mimeType;
                }
            }
        }

        // Fallback: Try to determine from filename
        $fileName = $this->getOriginalFileName($document);
        if (str_ends_with($fileName, '.jpg') || str_ends_with($fileName, '.jpeg')) {
            \Log::info('MIME type determined from filename: image/jpeg');
            return 'image/jpeg';
        }

        \Log::warning('Could not determine MIME type, using fallback');
        return 'image/jpeg'; // Default to image/jpeg for your case
    }

    /**
     * Get original file name without .enc extension
     */
    private function getOriginalFileName($document)
    {
        $fileName = $document->documentName;
        if (str_ends_with($fileName, '.enc')) {
            return substr($fileName, 0, -4); // Remove .enc extension
        }
        return $fileName;
    }

    /**
     * Validate MIME type for security
     */
    private function validateMimeType($mimeType, $documentId, $fileName)
    {
        \Log::info('Validating MIME type:', [
            'mime_type' => $mimeType,
            'document_id' => $documentId,
            'file_name' => $fileName,
            'allowed_types' => $this->allowedMimeTypes
        ]);

        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            Log::warning('Unsupported file type attempted', [
                'mime_type' => $mimeType,
                'document_id' => $documentId,
                'file_name' => $fileName,
                'allowed_types' => $this->allowedMimeTypes
            ]);
            throw new BadRequestException('File type not supported for preview. Type: ' . $mimeType);
        }

        \Log::info('MIME type validation passed');
    }

    /**
     * Get security headers based on file type
     */
    private function getSecurityHeaders($mimeType, $fileName, $fileSize)
    {
        $headers = [
            'Content-Type' => $mimeType,
            'Content-Length' => $fileSize,
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => "default-src 'none'",
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
        ];

        if (str_starts_with($mimeType, 'image/') || $mimeType === 'application/pdf') {
            $headers['Content-Disposition'] = 'inline; filename="' . basename($fileName) . '"';
        } else {
            $headers['Content-Disposition'] = 'inline; filename="preview_' . basename($fileName) . '"';
        }

        return $headers;
    }

    /**
     * Create secure streamed response for regular files
     */
    private function createSecureStreamedResponse($filePath, $headers)
    {
        return new StreamedResponse(function () use ($filePath) {
            try {
                // Use public disk
                $stream = Storage::disk('public')->readStream($filePath);

                if (!is_resource($stream)) {
                    throw new \Exception('Unable to open file stream');
                }

                while (!feof($stream)) {
                    echo fread($stream, 8192);
                    flush();
                }

                fclose($stream);
            } catch (\Exception $e) {
                Log::error('Stream error: ' . $e->getMessage());
                throw new BadRequestException('Unable to stream document');
            }
        }, 200, $headers);
    }

    /**
     * Log document access for audit trail
     */
    private function logDocumentAccess($document)
    {
        Log::info('Document accessed', [
            'document_id' => $document->id,
            'document_name' => $document->documentName,
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->getRoleNames()->first(),
            'ip_address' => request()->ip(),
            'accessed_at' => now()->toISOString()
        ]);
    }
}
