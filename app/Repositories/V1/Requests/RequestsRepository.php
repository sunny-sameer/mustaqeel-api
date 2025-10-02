<?php

namespace App\Repositories\V1\Requests;


use App\Models\Requests;

// Core Repository
use App\Repositories\V1\Core\CoreRepository;

class RequestsRepository extends CoreRepository implements RequestsInterface
{
    public function __construct(Requests $model)
    {
        parent::__construct($model);
    }
}
