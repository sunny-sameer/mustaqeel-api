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
}
