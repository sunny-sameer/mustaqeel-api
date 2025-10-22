<?php

namespace App\Repositories\V1\Artifacts;
use App\Repositories\V1\Core\CoreInterface;

interface ArtifactsInterface  extends CoreInterface {
    public function updateOrCreateDocuments($params = [], $request);
    public function updateDocuments($request, $reqId, $type);
    public function deleteDocuments($request, $type);
    public function deleteDocumentById($id);
}
