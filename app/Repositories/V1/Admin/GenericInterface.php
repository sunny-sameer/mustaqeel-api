<?php

namespace App\Repositories\V1\Admin;

use App\Repositories\V1\Core\CoreInterface;


interface GenericInterface extends CoreInterface
{
    // ===== CATEGORIES =====
    public function allCategories();
    public function findCategory($id);
    public function createCategory($data);
    public function updateCategory($id, $data);
    public function deleteCategory($id);

    // ===== SUBCATEGORIES =====
    public function allSubCategories();
    public function findSubCategory($id);
    public function createSubCategory($data);
    public function updateSubCategory($id, $data);
    public function deleteSubCategory($id);

    // ===== SECTORS =====
    public function allSectors();
    public function findSector($id);
    public function createSector($data);
    public function updateSector($id, $data);
    public function deleteSector($id);

    // ===== ACTIVITIES =====
    public function allActivities();
    public function findActivity($id);
    public function createActivity($data);
    public function updateActivity($id, $data);
    public function deleteActivity($id);

    // ===== SUBACTIVITIES =====
    public function allSubActivities();
    public function findSubActivity($id);
    public function createSubActivity($data);
    public function updateSubActivity($id, $data);
    public function deleteSubActivity($id);

    // ===== ENTITIES =====
    public function allEntities();
    public function findEntity($id);
    public function createEntity($data);
    public function updateEntity($id, $data);
    public function deleteEntity($id);

    // ===== INCUBATORS =====
    public function allIncubators();
    public function findIncubator($id);
    public function createIncubator($data);
    public function updateIncubator($id, $data);
    public function deleteIncubator($id);

    // ===== PIVOTS =====
    public function attachCategoriesToSector($sectorId, $categoryIds);
    public function attachEntitiesToActivity($activityId, $entityIds);
}
