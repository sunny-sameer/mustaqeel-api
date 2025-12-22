<?php

namespace App\Services\V1\Artifacts;

use App\Exceptions\BadRequestException;
use Illuminate\Http\Request;


use App\Services\V1\BaseService;


use App\Repositories\V1\Artifacts\ArtifactsInterface;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;


class ArtifactsService extends BaseService
{
    protected $artifactsInterface;


    public function __construct(
        ArtifactsInterface $artifactsInterface
    ) {
        $this->artifactsInterface = $artifactsInterface;
    }


    public function createDocuments(Request $request)
    {
        if ($request->hasFile('document')) {
            $file = $request->file('document');

            $filename = 'APP-DOC-' . time() . '-' . $request->key . '.' . $file->extension();
            $path = 'requests/documents';
            $storagePath = "{$filename}.enc";
            $fullFilePath = $path . '/' . $storagePath; // Store this

            $document = $this->artifactsInterface->getDocument(['entityId' => $request->entityId,'type' => $request->key,'entityType' => $request->entityType]);
            if(isset($document->id)){
                if(Storage::disk('public')->get($path.'/'.$document->documentName)){
                    Storage::disk('public')->delete($path.'/'.$document->documentName);
                }
            }

            $encryptedContent = Crypt::encrypt(file_get_contents($file));
            Storage::disk('public')->put($fullFilePath, $encryptedContent);

            // Store the full file path in meta
            $meta = [
                'extension' => $file->extension(),
                'file_path' => $fullFilePath, // Store the full path
                'disk' => 'public' // Store which disk was used
            ];

            $data = [
                'entityId' => $request->entityId,
                'documentName' => $storagePath,
                'type' => $request->key,
                'meta' => json_encode(array_filter($meta)),
                'entityType' => $request->entityType,
                'status' => true,
            ];

            $params = [
                'entityId' => $request->entityId,
                'type' => $request->key,
                'entityType' => $request->entityType,
            ];

            $document = $this->artifactsInterface->updateOrCreateDocuments($params, $data);

            return (object)['ok' => true, 'status' => 201, 'document' => $document];
        }

        return (object)['ok' => false, 'status' => 400, 'message' => 'Invalid document'];
    }
}
