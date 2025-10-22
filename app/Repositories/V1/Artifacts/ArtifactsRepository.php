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
}
