<?php

namespace App\Services\V1\Admin;


use App\Repositories\V1\Admin\GenericInterface;

class GenericService
{
    protected $genericInterface;

    public function __construct(GenericInterface $genericInterface)
    {
        $this->genericInterface = $genericInterface;
    }

    // Category
    public function allCategories($paginate = 10)
    {
        return $this->genericInterface->allCategories($paginate);
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
    public function allSubCategories($paginate = 10)
    {
        return $this->genericInterface->allSubCategories($paginate);
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
    public function allSectors($paginate = 10)
    {
        return $this->genericInterface->allSectors($paginate);
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
    public function allActivities($paginate = 10)
    {
        return $this->genericInterface->allActivities($paginate);
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
    public function allSubActivities($paginate = 10)
    {
        return $this->genericInterface->allSubActivities($paginate);
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
    public function allEntities($paginate = 10)
    {
        return $this->genericInterface->allEntities($paginate);
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
    public function allIncubators($paginate = 10)
    {
        return $this->genericInterface->allIncubators($paginate);
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

    // Pivot helpers
    public function attachCategoriesToSector($sectorId, $categoryIds)
    {
        return $this->genericInterface->attachCategoriesToSector($sectorId, $categoryIds);
    }
    public function attachEntitiesToActivity($activityId, $entityIds)
    {
        return $this->genericInterface->attachEntitiesToActivity($activityId, $entityIds);
    }
}
