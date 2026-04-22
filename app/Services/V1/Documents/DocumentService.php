<?php

namespace App\Services\V1\Documents;

use App\Exceptions\DocumentNotFoundException;
use App\Exceptions\DocumentAccessDeniedException;
use App\Exceptions\BadRequestException;
use App\Models\Documents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\FacadesLog;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class DocumentService
{
    protected $currentDocument;
    protected $quesId;
    protected $documentId;
    protected $documentName;
    protected $filePath;

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
    public function getDocumentPreview()
    {
        $filePath = $this->getFilePathFromDocument();

        $this->validateFileExists();

        // Check if file is encrypted
        $isEncrypted = $this->isFileEncrypted();

        if ($isEncrypted) {
            return $this->handleEncryptedFilePreview();
        } else {
            return $this->handleRegularFilePreview();
        }
    }

    /**
     * Validate document access for fluent interface
     */
    public function validateDocumentAccess(Request $request, $documentId)
    {
        $this->quesId = $request->quesId ?? null;
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

        $this->documentId = $document->id;
        $user = auth()->user();
        if (!$this->userCanAccessDocument($user, $document)) {
            Log::warning('Document access denied', [
                'userId' => $user->id,
                'documentId' => $this->documentId,
                'userRole' => $user->getRoleNames()->first()
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

        // Applicant can only access their own documents
        if ($user->hasRole('applicant')) {
            return $this->isDocumentOwner($user, $document);
        }else{
            return true;
        }

    }

    /**
     * Check if applicant owns this document
     */
    private function isDocumentOwner($user, $document)
    {
        // Check if document belongs to user's request/application
        if ($document->entityId) {
            $request = \App\Models\Requests::find($document->entityId);
            if ($request && $request->userId === $user->id) {
                return true;
            }
        }

        // Alternative: Check if document has direct user relationship
        if (isset($document->userId) && $document->userId === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Extract file path from document meta or documentName
     */
    private function getFilePathFromDocument()
    {
        $this->documentName = $this->currentDocument->documentName;
        $meta = json_decode($this->currentDocument->meta,true);

        // Files are stored in public disk
        $storageDisk = 'public';
        $basePath = 'requests/documents/';

        if(!empty($this->quesId)){
            foreach ($meta as $key => $value) {
                if(isset($value['id']) && $value['id'] == $this->quesId) {
                    $this->documentName = $value['documentName'] ?? $this->currentDocument->documentName;
                    $basePath = 'requests/documents/additional/';
                    $this->filePath = $value['file_path'];
                }
            }
        }else{
            if ($meta && is_array($meta)) {
                if (isset($meta['file_path'])) {
                    $filePath = $meta['file_path'];
                    Log::info('Found file path in meta:', ['file_path' => $filePath]);
                    $this->filePath = $filePath;
                }
            }
        }

        Log::info('Getting file path for document:', [
            'documentId' => $this->documentId,
            'documentName' => $this->documentName,
            'meta' => $meta
        ]);


        // Construct the file path
        $filePath = $this->filePath ? $this->filePath : $basePath . $this->documentName;

        Log::info('Checking file path:', [
            'disk' => $storageDisk,
            'filePath' => $filePath,
            'exists' => Storage::disk($storageDisk)->exists($filePath)
        ]);

        if (!Storage::disk($storageDisk)->exists($filePath)) {
            Log::error('Document file not found:', [
                'disk' => $storageDisk,
                'filePath' => $filePath,
                'availableFiles' => Storage::disk(name: $storageDisk)->files($basePath)
            ]);
            throw new DocumentNotFoundException('Document file not found in storage. Path: ' . $filePath);
        }

        return $filePath;
    }

    /**
     * Check if file is encrypted (has .enc extension)
     */
    private function isFileEncrypted()
    {
        return str_ends_with($this->filePath, '.enc');
    }

    /**
     * Handle preview for encrypted files
     */
    private function handleEncryptedFilePreview()
    {
        try {
            Log::info('Starting encrypted file preview:', [
                'filePath' => $this->filePath,
                'documentId' => $this->documentId
            ]);

            // Read and decrypt the file from public disk
            $encryptedContent = Storage::disk('public')->get($this->filePath);

            Log::info('Encrypted content read:', [
                'contentSize' => strlen($encryptedContent),
                'filePath' => $this->filePath
            ]);

            $decryptedContent = Crypt::decrypt($encryptedContent);

            Log::info('File decrypted successfully:', [
                'decryptedSize' => strlen($decryptedContent)
            ]);

            // Get file size from decrypted content
            $fileSize = strlen($decryptedContent);

            // Validate file size
            if ($fileSize > $this->maxFileSize) {
                throw new BadRequestException('File too large for preview. Maximum size is 10MB.');
            }
            
            // Determine MIME type from document meta
            $mimeType = $this->getMimeTypeFromDocument();
            Log::info('MIME type determined:', ['mimeType' => $mimeType]);

            $this->validateMimeType($mimeType);

            $headers = $this->getSecurityHeaders($mimeType, $this->getOriginalFileName(), $fileSize);

            $this->logDocumentAccess($this->currentDocument);

            Log::info('Returning successful preview response');

            // Create response with decrypted content
            return new StreamedResponse(function () use ($decryptedContent) {
                echo $decryptedContent;
            }, 200, $headers);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            Log::error('Decryption failed:', [
                'error' => $e->getMessage(),
                'filePath' => $this->filePath,
                'documentId' => $this->documentId
            ]);
            throw new BadRequestException('Unable to decrypt document: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Encrypted file preview error:', [
                'error' => $e->getMessage(),
                'filePath' => $this->filePath,
                'documentId' => $this->documentId,
                'trace' => $e->getTraceAsString()
            ]);
            throw new BadRequestException('Unable to preview document: ' . $e->getMessage());
        }
    }

    /**
     * Handle preview for regular (non-encrypted) files
     */
    private function handleRegularFilePreview()
    {
        // Use public disk for file operations
        $fileSize = Storage::disk('public')->size($this->filePath);
        if ($fileSize > $this->maxFileSize) {
            throw new BadRequestException('File too large for preview. Maximum size is 10MB.');
        }

        $mimeType = Storage::disk('public')->mimeType($this->filePath);
        $this->validateMimeType($mimeType);

        $headers = $this->getSecurityHeaders($mimeType, $this->documentName, $fileSize);

        $this->logDocumentAccess($this->currentDocument);

        return $this->createSecureStreamedResponse($this->filePath, $headers);
    }

    /**
     * Validate file exists in storage
     */
    private function validateFileExists()
    {
        if (!Storage::disk('public')->exists($this->filePath)) {
            throw new DocumentNotFoundException('Document file not found in storage: ' . $this->filePath);
        }
    }

    /**
     * Get MIME type from document meta or file extension
     */
    private function getMimeTypeFromDocument()
    {
        Log::info('Getting MIME type for document:', [
            'documentId' => $this->documentId,
            'meta' => $this->currentDocument->meta,
            'documentName' => $this->documentName
        ]);

        // Try to get from meta first
        if ($this->currentDocument->meta) {
            $metaArray = is_array($this->currentDocument->meta) ? $this->currentDocument->meta : json_decode($this->currentDocument->meta, true);

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
                    Log::info('MIME type determined from extension:', [
                        'extension' => $extension,
                        'mimeType' => $mimeType
                    ]);
                    return $mimeType;
                }
            }
        }

        // Fallback: Try to determine from filename
        $fileName = $this->getOriginalFileName();
        if (str_ends_with($fileName, '.jpg') || str_ends_with($fileName, '.jpeg')) {
            Log::info('MIME type determined from filename: image/jpeg');
            return 'image/jpeg';
        }

        Log::warning('Could not determine MIME type, using fallback');
        return 'image/jpeg'; // Default to image/jpeg for your case
    }

    /**
     * Get original file name without .enc extension
     */
    private function getOriginalFileName()
    {
        if (str_ends_with($this->documentName, '.enc')) {
            return substr($this->documentName, 0, -4); // Remove .enc extension
        }
        return $this->documentName;
    }

    /**
     * Validate MIME type for security
     */
    private function validateMimeType($mimeType)
    {
        Log::info('Validating MIME type:', [
            'mimeType' => $mimeType,
            'documentId' => $this->documentId,
            'fileName' => $this->documentName,
            'allowedTypes' => $this->allowedMimeTypes
        ]);

        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            Log::warning('Unsupported file type attempted', [
                'mimeType' => $mimeType,
                'documentId' => $this->documentId,
                'fileName' => $this->documentName,
                'allowedTypes' => $this->allowedMimeTypes
            ]);
            throw new BadRequestException('File type not supported for preview. Type: ' . $mimeType);
        }

        Log::info('MIME type validation passed');
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
            'documentId' => $document->id,
            'documentName' => $document->documentName,
            'userId' => auth()->id(),
            'userRole' => auth()->user()->getRoleNames()->first(),
            'ipAddress' => request()->ip(),
            'accessedAt' => now()->toISOString()
        ]);
    }
}
