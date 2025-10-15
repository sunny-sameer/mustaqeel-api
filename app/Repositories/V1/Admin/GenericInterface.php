<?php

namespace App\Repositories\V1\Admin;

use App\Repositories\V1\Core\CoreInterface;


interface GenericInterface extends CoreInterface
{
    // ===== CATEGORIES =====
    public function allCategories($paginate);
    public function findCategory($id);
    public function createCategory($data);
    public function updateCategory($id, $data);
    public function deleteCategory($id);

    // ===== SUBCATEGORIES =====
    public function allSubCategories($paginate);
    public function findSubCategory($id);
    public function createSubCategory($data);
    public function updateSubCategory($id, $data);
    public function deleteSubCategory($id);

    // ===== SECTORS =====
    public function allSectors($paginate);
    public function findSector($id);
    public function createSector($data);
    public function updateSector($id, $data);
    public function deleteSector($id);

    // ===== ACTIVITIES =====
    public function allActivities($paginate);
    public function findActivity($id);
    public function createActivity($data);
    public function updateActivity($id, $data);
    public function deleteActivity($id);

    // ===== SUBACTIVITIES =====
    public function allSubActivities($paginate);
    public function findSubActivity($id);
    public function createSubActivity($data);
    public function updateSubActivity($id, $data);
    public function deleteSubActivity($id);

    // ===== ENTITIES =====
    public function allEntities($paginate);
    public function findEntity($id);
    public function createEntity($data);
    public function updateEntity($id, $data);
    public function deleteEntity($id);

    // ===== INCUBATORS =====
    public function allIncubators($paginate);
    public function findIncubator($id);
    public function createIncubator($data);
    public function updateIncubator($id, $data);
    public function deleteIncubator($id);

    // ===== PIVOTS =====
    public function attachCategoriesToSector($sectorId, $categoryIds);
    public function attachEntitiesToActivity($activityId, $entityIds);
    public function attachActivitiesToEntity($entityId, $activityIds);

    // ===== REQUEST METAS =====
    public function getAllNationalities();
    public function getAllCategories();
    public function getAllSectorsSubCategoriesAndIncubators($catSlug);
    public function getAllActivities($secSlug);
    public function getAllEntitiesAndSubActivities($actSlug);

    public function getAllActivitiesWithEntity($entSlug);
}
