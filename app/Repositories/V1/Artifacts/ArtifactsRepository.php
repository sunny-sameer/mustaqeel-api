<?php

namespace App\Repositories\V1\Artifacts;


use App\Models\Documents;


// Core Repository
use App\Repositories\V1\Core\CoreRepository;


class ArtifactsRepository extends CoreRepository implements ArtifactsInterface
{

    public function __construct(Documents $model)
    {
        parent::__construct($model);
    }

    public function updateOrCreateDocuments($params = [], $request)
    {
        return $this->model->updateOrCreate($params,$request);
    }

    public function updateDocuments($request, $reqId, $type)
    {
        $reuploadDocuments = $request->reuploadDocument;
        $data = [];
        foreach ($reuploadDocuments as $key => $value) {
            $document = $this->model->where(['entityId'=>$reqId,'type'=>$value['type'],'entityType'=>$type])->first();
            if(isset($document->id)){
                unset($value['type']);
                $meta = json_decode($document->meta,true);
                $meta = array_merge($meta, $value);
                $meta['status'] = 'Reupload';
                $document->update(['meta'=>json_encode(array_filter($meta))]);

                $data[] = $document;
            }
        }

        if(empty($data)){
            return false;
        }
        return $data;
    }

    public function deleteDocuments($request, $type)
    {
        $documents = $request->documents;
        $keys = [];
        if(isset($request->id) && !empty($documents)){
            foreach ($documents as $key => $value) {
                array_push($keys,$key);
            }
        }

        if(!empty($keys)){
            $this->model->where('entityId',$request->id)
            ->where('entityType',$type)
            ->whereNotIn('type',$keys)
            ->delete();
        }

        return 'Deleted';
    }

    public function deleteDocumentById($id)
    {
        $document = $this->model->find($id);
        $documentName = ucfirst(camelCaseToSpace($document->type));
        $document->delete();
        return $documentName;
    }
}
