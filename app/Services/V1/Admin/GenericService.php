<?php

namespace App\Services\V1\Admin;

use App\DTOs\V1\Requests\FormFieldsDTO;
use App\DTOs\V1\Requests\FormFieldsMetaDTO;
use App\Models\FormFieldMeta;
use App\Models\FormFields;
use App\Repositories\V1\Admin\GenericInterface;
use App\Repositories\V1\Requests\RequestsInterface;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class GenericService
{
    protected $genericInterface;
    protected $requestsInterface;

    public function __construct(GenericInterface $genericInterface, RequestsInterface $requestsInterface)
    {
        $this->genericInterface = $genericInterface;
        $this->requestsInterface = $requestsInterface;
    }

    // Category
    public function allCategories($request)
    {
        return $this->genericInterface->allCategories($request);
    }
    public function findCategory($id)
    {
        return $this->genericInterface->findCategory($id);
    }
    public function createCategory($data)
    {
        return $this->genericInterface->createCategory($data);
    }
    public function updateCategory($id, $data)
    {
        return $this->genericInterface->updateCategory($id, $data);
    }
    public function deleteCategory($id)
    {
        return $this->genericInterface->deleteCategory($id);
    }

    // SubCategory
    public function allSubCategories($request)
    {
        return $this->genericInterface->allSubCategories($request);
    }
    public function findSubCategory($id)
    {
        return $this->genericInterface->findSubCategory($id);
    }
    public function createSubCategory($data)
    {
        return $this->genericInterface->createSubCategory($data);
    }
    public function updateSubCategory($id, $data)
    {
        return $this->genericInterface->updateSubCategory($id, $data);
    }
    public function deleteSubCategory($id)
    {
        return $this->genericInterface->deleteSubCategory($id);
    }

    // Sectors
    public function allSectors($request)
    {
        return $this->genericInterface->allSectors($request);
    }
    public function findSector($id)
    {
        return $this->genericInterface->findSector($id);
    }
    public function createSector($data)
    {
        return $this->genericInterface->createSector($data);
    }
    public function updateSector($id, $data)
    {
        return $this->genericInterface->updateSector($id, $data);
    }
    public function deleteSector($id)
    {
        return $this->genericInterface->deleteSector($id);
    }

    // Activities
    public function allActivities($request)
    {
        return $this->genericInterface->allActivities($request);
    }
    public function findActivity($id)
    {
        return $this->genericInterface->findActivity($id);
    }
    public function createActivity($data)
    {
        return $this->genericInterface->createActivity($data);
    }
    public function updateActivity($id, $data)
    {
        return $this->genericInterface->updateActivity($id, $data);
    }
    public function deleteActivity($id)
    {
        return $this->genericInterface->deleteActivity($id);
    }

    // SubActivities
    public function allSubActivities($request)
    {
        return $this->genericInterface->allSubActivities($request);
    }
    public function findSubActivity($id)
    {
        return $this->genericInterface->findSubActivity($id);
    }
    public function createSubActivity($data)
    {
        return $this->genericInterface->createSubActivity($data);
    }
    public function updateSubActivity($id, $data)
    {
        return $this->genericInterface->updateSubActivity($id, $data);
    }
    public function deleteSubActivity($id)
    {
        return $this->genericInterface->deleteSubActivity($id);
    }

    // Entities
    public function allEntities($request)
    {
        return $this->genericInterface->allEntities($request);
    }
    public function findEntity($id)
    {
        return $this->genericInterface->findEntity($id);
    }
    public function createEntity($data)
    {
        return $this->genericInterface->createEntity($data);
    }
    public function updateEntity($id, $data)
    {
        return $this->genericInterface->updateEntity($id, $data);
    }
    public function deleteEntity($id)
    {
        return $this->genericInterface->deleteEntity($id);
    }

    // Incubators
    public function allIncubators($request)
    {
        return $this->genericInterface->allIncubators($request);
    }
    public function findIncubator($id)
    {
        return $this->genericInterface->findIncubator($id);
    }
    public function createIncubator($data)
    {
        return $this->genericInterface->createIncubator($data);
    }
    public function updateIncubator($id, $data)
    {
        return $this->genericInterface->updateIncubator($id, $data);
    }
    public function deleteIncubator($id)
    {
        return $this->genericInterface->deleteIncubator($id);
    }

    // ===== FORM FIELDS - Now clean and consistent =====
    public function allFormFields($request)
    {
        return $this->genericInterface->allFormFields($request);
    }

    public function findFormField($id)
    {
        return $this->genericInterface->findFormField($id);
    }

    public function createFormField($data)
    {
        return $this->genericInterface->createFormField($data);
    }

    public function updateFormField($id, $data)
    {
        return $this->genericInterface->updateFormField($id, $data);
    }

    public function deleteFormField($id)
    {
        return $this->genericInterface->deleteFormField($id);
    }

    public function updateOrCreateFormFieldMetaData($data, $formFieldId)
    {
        return $this->genericInterface->updateOrCreateFormFieldMetaData($data, $formFieldId);
    }

    public function getFormStructure(array $params): array
    {
        return $this->genericInterface->getFormStructure($params);
    }

    // Stages
    public function allStages($request)
    {
        return $this->genericInterface->allStages($request);
    }
    public function findStage($id)
    {
        return $this->genericInterface->findStage($id);
    }
    public function createStage($data)
    {
        return $this->genericInterface->createStage($data);
    }
    public function updateStage($id, $data)
    {
        return $this->genericInterface->updateStage($id, $data);
    }
    public function deleteStage($id)
    {
        return $this->genericInterface->deleteStage($id);
    }

    // Stage Statuses
    public function allStageStatuses($request)
    {
        return $this->genericInterface->allStageStatuses($request);
    }
    public function findStageStatus($id)
    {
        return $this->genericInterface->findStageStatus($id);
    }
    public function createStageStatus($data)
    {
        return $this->genericInterface->createStageStatus($data);
    }
    public function updateStageStatus($id, $data)
    {
        return $this->genericInterface->updateStageStatus($id, $data);
    }
    public function deleteStageStatus($id)
    {
        return $this->genericInterface->deleteStageStatus($id);
    }

    // Pivot helpers
    public function attachCategoriesToSector($sectorId, $categoryIds)
    {
        return $this->genericInterface->attachCategoriesToSector($sectorId, $categoryIds);
    }
    public function attachEntitiesToActivity($activityId, $entityIds)
    {
        return $this->genericInterface->attachEntitiesToActivity($activityId, $entityIds);
    }
    public function attachActivitiesToEntity($entityId, $activityIds)
    {
        return $this->genericInterface->attachActivitiesToEntity($entityId, $activityIds);
    }
}
