<?php

namespace App\Repositories\V1\Admin;


use App\Repositories\V1\Core\CoreRepository;


use App\Models\Categories as Category;
use App\Models\SubCategories as SubCategory;
use App\Models\Sectors as Sector;
use App\Models\Activities as Activity;
use App\Models\SubActivities as SubActivity;
use App\Models\Entities as Entity;
use App\Models\Incubator;
use Illuminate\Support\Arr;

class GenericRepository extends CoreRepository implements GenericInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }
    // ===== CATEGORIES =====
    public function allCategories($paginate)
    {
        return Category::paginate($paginate);
    }
    public function findCategory($id)
    {
        return Category::findOrFail($id);
    }
    public function createCategory($data)
    {
        // $data['slug'] = \Str::slug($data['name']);
        return Category::create($data);
    }
    public function updateCategory($id, $data)
    {
        $cat = Category::findOrFail($id);
        // $data['slug'] = \Str::slug($data['name']);
        $cat->update($data);
        return $cat;
    }
    public function deleteCategory($id)
    {
        return Category::findOrFail($id)->delete();
    }

    // ===== SUBCATEGORIES =====
    public function allSubCategories($paginate)
    {
        return SubCategory::with('category')->paginate($paginate);
    }
    public function findSubCategory($id)
    {
        return SubCategory::with('category')->findOrFail($id);
    }
    public function createSubCategory($data)
    {
        // $data['slug'] = \Str::slug($data['name']);
        return SubCategory::create($data);
    }
    public function updateSubCategory($id, $data)
    {
        $sub = SubCategory::findOrFail($id);
        // $data['slug'] = \Str::slug($data['name']);
        $sub->update($data);
        return $sub;
    }
    public function deleteSubCategory($id)
    {
        return SubCategory::findOrFail($id)->delete();
    }

    // ===== SECTORS =====
    public function allSectors($paginate)
    {
        return Sector::with('categories')->paginate($paginate)->through(function ($sectors) {
            $sectors->categoryIds = $sectors->categories->pluck('id');
            return $sectors;
        });
    }
    public function findSector($id)
    {
        $sector = Sector::with('categories')->findOrFail($id);
        $sector->categoryIds = $sector->categories->pluck('id');

        return $sector;
    }
    public function createSector($data)
    {
        // $data['slug'] = \Str::slug($data['name']);
        $sectorData = Arr::except($data, ['categoryIds']);
        $sector = Sector::create($sectorData);
        if (isset($data['categoryIds'])) $sector->categories()->sync($data['categoryIds']);
        return $sector;
    }
    public function updateSector($id, $data)
    {
        $sector = Sector::findOrFail($id);
        // if (isset($data['name'])) $data['slug'] = \Str::slug($data['name']);
        $sectorData = Arr::except($data, ['categoryIds']);
        $sector->update($sectorData);
        if (isset($data['categoryIds'])) $sector->categories()->sync([]); $sector->categories()->sync($data['categoryIds']);
        return $sector;
    }
    public function deleteSector($id)
    {
        return Sector::findOrFail($id)->delete();
    }

    // ===== ACTIVITIES =====
    public function allActivities($paginate)
    {
        return Activity::with('sector', 'entities', 'subActivities')->paginate($paginate)->through(function ($activities) {
            $activities->entityIds = $activities->entities->pluck('id');
            return $activities;
        });
    }
    public function findActivity($id)
    {
        $activity = Activity::with('sector', 'entities', 'subActivities')->findOrFail($id);
        $activity->entityIds = $activity->entities->pluck('id');

        return $activity;
    }
    public function createActivity($data)
    {
        // $data['slug'] = \Str::slug($data['nameEn']);
        $activityData = Arr::except($data, ['entityIds']);
        $activity = Activity::create($activityData);
        if (isset($data['entityIds'])) $activity->entities()->sync($data['entityIds']);
        return $activity;
    }
    public function updateActivity($id, $data)
    {
        $activity = Activity::findOrFail($id);
        // if (isset($data['nameEn'])) $data['slug'] = \Str::slug($data['nameEn']);
        $activityData = Arr::except($data, ['entityIds']);
        $activity->update($activityData);
        if (isset($data['entityIds'])) $activity->entities()->sync([]); $activity->entities()->sync($data['entityIds']);
        return $activity;
    }
    public function deleteActivity($id)
    {
        return Activity::findOrFail($id)->delete();
    }

    // ===== SUBACTIVITIES =====
    public function allSubActivities($paginate)
    {
        return SubActivity::with('activity')->paginate($paginate);
    }
    public function findSubActivity($id)
    {
        return SubActivity::with('activity')->findOrFail($id);
    }
    public function createSubActivity($data)
    {
        return SubActivity::create($data);
    }
    public function updateSubActivity($id, $data)
    {
        $sub = SubActivity::findOrFail($id);
        $sub->update($data);
        return $sub;
    }
    public function deleteSubActivity($id)
    {
        return SubActivity::findOrFail($id)->delete();
    }

    // ===== ENTITIES =====
    public function allEntities($paginate)
    {
        return Entity::with('activities')->paginate($paginate)->through(function ($entities) {
            $entities->activityIds = $entities->activities->pluck('id');
            return $entities;
        });
    }
    public function findEntity($id)
    {
        $entity = Entity::with('activities')->findOrFail($id);
        $entity->activityIds = $entity->activities->pluck('id');

        return $entity;
    }
    public function createEntity($data)
    {
        $entityData = Arr::except($data, ['activityIds']);
        $entity = Entity::create($entityData);
        if (isset($data['activityIds'])) $entity->activities()->sync($data['activityIds']);
        return $entity;
    }
    public function updateEntity($id, $data)
    {
        $entity = Entity::findOrFail($id);

        $entityData = Arr::except($data, ['activityIds']);
        $entity->update($entityData);
        if (isset($data['activityIds'])) $entity->activities()->sync([]); $entity->activities()->sync($data['activityIds']);

        return $entity;
    }
    public function deleteEntity($id)
    {
        return Entity::findOrFail($id)->delete();
    }

    // ===== INCUBATORS =====
    public function allIncubators($paginate)
    {
        return Incubator::with('category')->paginate($paginate);
    }
    public function findIncubator($id)
    {
        return Incubator::with('category')->findOrFail($id);
    }
    public function createIncubator($data)
    {
        return Incubator::create($data);
    }
    public function updateIncubator($id, $data)
    {
        $inc = Incubator::findOrFail($id);
        $inc->update($data);
        return $inc;
    }
    public function deleteIncubator($id)
    {
        return Incubator::findOrFail($id)->delete();
    }

    // ===== PIVOTS (category_sector / activity_entity) =====
    public function attachCategoriesToSector($sectorId, $categoryIds)
    {
        $sector = Sector::findOrFail($sectorId);
        $sector->categories()->sync([]);
        return $sector->categories()->sync($categoryIds);
    }
    public function attachEntitiesToActivity($activityId, $entityIds)
    {
        $activity = Activity::findOrFail($activityId);
        $activity->entities()->sync([]);
        return $activity->entities()->sync($entityIds);
    }

    public function attachActivitiesToEntity($entityId, $activityIds)
    {
        $entity = Entity::findOrFail($entityId);
        $entity->activities()->sync([]);
        return $entity->activities()->sync($activityIds);
    }
}
