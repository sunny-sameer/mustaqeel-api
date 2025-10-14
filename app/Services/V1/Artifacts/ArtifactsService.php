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
        if($request->hasFile('document'))
        {
            $file = $request->file('document');

            $filename = 'APP-DOC-'.time().'-'.$request->key.'.'.$file->extension();

            $path = 'requests/documents';


            $storagePath = "{$filename}.enc";
            $encryptedContent = Crypt::encrypt(file_get_contents($file));
            Storage::disk('public')->put($path.'/'.$storagePath, $encryptedContent);

            $meta = [ 'extension'=>$file->extension() ];

            $data = [
                'entityId'=> $request->entityId,
                'documentName'=> $storagePath,
                'type'=> $request->key,
                'meta'=> json_encode(array_filter($meta)),
                'entityType'=> $request->entityType,
                'status'=> true,
            ];

            $params = [
                'entityId'=> $request->entityId,
                'type'=> $request->key,
                'entityType'=> $request->entityType,
            ];

            $document = $this->artifactsInterface->updateOrCreateDocuments($params,$data);

            return (object)['ok' => true, 'status' => 201, 'document' => $document];
        }

        return (object)['ok' => false, 'status' => 400, 'message' => 'Invalid document'];
    }
}
