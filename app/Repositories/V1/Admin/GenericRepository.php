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

class GenericRepository extends CoreRepository implements GenericInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }
    // ===== CATEGORIES =====
    public function allCategories()
    {
        return Category::all();
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
    public function allSubCategories()
    {
        return SubCategory::all();
    }
    public function findSubCategory($id)
    {
        return SubCategory::findOrFail($id);
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
    public function allSectors()
    {
        return Sector::with('categories')->get();
    }
    public function findSector($id)
    {
        return Sector::with('categories')->findOrFail($id);
    }
    public function createSector($data)
    {
        // $data['slug'] = \Str::slug($data['name']);
        $sector = Sector::create($data);
        if (isset($data['categoryIds'])) $sector->categories()->sync($data['categoryIds']);
        return $sector;
    }
    public function updateSector($id, $data)
    {
        $sector = Sector::findOrFail($id);
        // if (isset($data['name'])) $data['slug'] = \Str::slug($data['name']);
        $sector->update($data);
        if (isset($data['categoryIds'])) $sector->categories()->sync($data['categoryIds']);
        return $sector;
    }
    public function deleteSector($id)
    {
        return Sector::findOrFail($id)->delete();
    }

    // ===== ACTIVITIES =====
    public function allActivities()
    {
        return Activity::with('sector', 'entities', 'subActivities')->get();
    }
    public function findActivity($id)
    {
        return Activity::with('sector', 'entities', 'subActivities')->findOrFail($id);
    }
    public function createActivity($data)
    {
        $data['slug'] = \Str::slug($data['nameEn']);
        $activity = Activity::create($data);
        if (isset($data['entityIds'])) $activity->entities()->sync($data['entityIds']);
        return $activity;
    }
    public function updateActivity($id, $data)
    {
        $activity = Activity::findOrFail($id);
        // if (isset($data['nameEn'])) $data['slug'] = \Str::slug($data['nameEn']);
        $activity->update($data);
        if (isset($data['entityIds'])) $activity->entities()->sync($data['entityIds']);
        return $activity;
    }
    public function deleteActivity($id)
    {
        return Activity::findOrFail($id)->delete();
    }

    // ===== SUBACTIVITIES =====
    public function allSubActivities()
    {
        return SubActivity::all();
    }
    public function findSubActivity($id)
    {
        return SubActivity::findOrFail($id);
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
    public function allEntities()
    {
        return Entity::all();
    }
    public function findEntity($id)
    {
        return Entity::findOrFail($id);
    }
    public function createEntity($data)
    {
        return Entity::create($data);
    }
    public function updateEntity($id, $data)
    {
        $ent = Entity::findOrFail($id);
        $ent->update($data);
        return $ent;
    }
    public function deleteEntity($id)
    {
        return Entity::findOrFail($id)->delete();
    }

    // ===== INCUBATORS =====
    public function allIncubators()
    {
        return Incubator::all();
    }
    public function findIncubator($id)
    {
        return Incubator::findOrFail($id);
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
        return $sector->categories()->syncWithoutDetaching($categoryIds);
    }
    public function attachEntitiesToActivity($activityId, $entityIds)
    {
        $activity = Activity::findOrFail($activityId);
        return $activity->entities()->syncWithoutDetaching($entityIds);
    }
}
