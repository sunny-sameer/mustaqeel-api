<?php

namespace App\Repositories\V1\Admin;


use App\Repositories\V1\Core\CoreRepository;


use App\Models\Categories as Category;
use App\Models\SubCategories as SubCategory;
use App\Models\Sectors as Sector;
use App\Models\Activities as Activity;
use App\Models\SubActivities as SubActivity;
use App\Models\Entities as Entity;
use App\Models\FormFields;
use App\Models\Incubator;
use App\Models\Nationality;
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
        return Category::create($data);
    }
    public function updateCategory($id, $data)
    {
        $cat = Category::findOrFail($id);
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
        $subCat = SubCategory::create($data);

        return $this->findSubCategory($subCat->id);
    }
    public function updateSubCategory($id, $data)
    {
        $subCat = SubCategory::findOrFail($id);
        $subCat->update($data);

        return $this->findSubCategory($subCat->id);
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
        $sectorData = Arr::except($data, ['categoryIds']);
        $sector = Sector::create($sectorData);
        if (isset($data['categoryIds'])) {
            $sector->categories()->sync($data['categoryIds']);
        }

        return $this->findSector($sector->id);
    }
    public function updateSector($id, $data)
    {
        $sector = Sector::findOrFail($id);

        $sectorData = Arr::except($data, ['categoryIds']);
        $sector->update($sectorData);
        if (isset($data['categoryIds'])) {
            $sector->categories()->sync([]);
            $sector->categories()->sync($data['categoryIds']);
        }

        return $this->findSector($sector->id);
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
        $activityData = isset($data['entityIds']) ? Arr::except($data, ['entityIds']) : $data;
        $activity = Activity::create($activityData);
        if (isset($data['entityIds'])) {
            $activity->entities()->sync($data['entityIds']);
        }

        return $this->findActivity($activity->id);
    }
    public function updateActivity($id, $data)
    {
        $activity = Activity::findOrFail($id);

        $activityData = isset($data['entityIds']) ? Arr::except($data, ['entityIds']) : $data;
        $activity->update($activityData);
        if (isset($data['entityIds'])) {
            $activity->entities()->sync([]);
            $activity->entities()->sync($data['entityIds']);
        }

        return $this->findActivity($activity->id);
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
        $sub = SubActivity::create($data);

        return $this->findSubActivity($sub->id);
    }
    public function updateSubActivity($id, $data)
    {
        $sub = SubActivity::findOrFail($id);
        $sub->update($data);

        return $this->findSubActivity($sub->id);
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

        return $this->findEntity($entity->id);
    }
    public function updateEntity($id, $data)
    {
        $entity = Entity::findOrFail($id);

        $entityData = Arr::except($data, ['activityIds']);
        $entity->update($entityData);
        if (isset($data['activityIds'])) $entity->activities()->sync([]); $entity->activities()->sync($data['activityIds']);

        return $this->findEntity($entity->id);
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
        $inc = Incubator::create($data);

        return $this->findIncubator($inc->id);
    }
    public function updateIncubator($id, $data)
    {
        $inc = Incubator::findOrFail($id);
        $inc->update($data);

        return $this->findIncubator($inc->id);
    }
    public function deleteIncubator($id)
    {
        return Incubator::findOrFail($id)->delete();
    }

    // ===== FORM FIELDS =====
    public function allFormFields($paginate)
    {
        $ff = FormFields::with([
            'formMetas.category:name,nameAr,slug',
            'formMetas.subCategory:name,nameAr,slug',
            'formMetas.sector:name,nameAr,slug',
            'formMetas.activity:name,nameAr,slug',
            'formMetas.subActivity:name,nameAr,slug',
            'formMetas.entity:name,nameAr,slug',
            'formMetas.incubator:name,nameAr,slug',
        ])->paginate($paginate);


        $ff->map(function ($query){
            $query->meta = $query->meta ? json_decode($query->meta) : NULL;

            return $query;
        });

        return $ff;
    }
    public function findFormField($id)
    {
        $ff = FormFields::with([
            'formMetas.category:name,nameAr,slug',
            'formMetas.subCategory:name,nameAr,slug',
            'formMetas.sector:name,nameAr,slug',
            'formMetas.activity:name,nameAr,slug',
            'formMetas.subActivity:name,nameAr,slug',
            'formMetas.entity:name,nameAr,slug',
            'formMetas.incubator:name,nameAr,slug',
        ])->findOrFail($id);


        $ff->meta = $ff->meta ? json_decode($ff->meta) : NULL;

        return $ff;
    }
    public function createFormField($data)
    {
        $ff = FormFields::create($data);

        return $ff;
    }
    public function updateFormField($id, $data)
    {
        $ff = FormFields::findOrFail($id);
        $ff->update($data);

        return $ff;
    }
    public function deleteFormField($id)
    {
        $ff = FormFields::findOrFail($id);
        $ff->formMetas()->delete();
        return $ff->delete();
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


    // ===== REQUEST METAS =====
    public function getAllNationalities()
    {
        return Nationality::select('name','phonecode')->get();
    }

    public function getAllCategories()
    {
        return Category::select('id','slug','name','nameAr')
        ->where('status',true)
        ->get();
    }

    public function getAllSectorsSubCategoriesAndIncubators($catSlug)
    {
        $category =  Category::where('slug',$catSlug)
        ->first();

        if(isset($category->id)){
            $catId = $category->id;
            $sectors = Sector::whereHas('categories', function ($query) use ($catId) {
                $query->where('categoryId', $catId);
            })
            ->select('id','slug', 'name', 'nameAr')
            ->where('status',true)
            ->get();

            $subCategories =  SubCategory::select('id','slug','name','nameAr')
            ->where('categoryId', $catId)
            ->where('status',true)
            ->get();

            $incubator =  Incubator::select('id','slug','name','nameAr')
            ->where('categoryId', $catId)
            ->where('status',true)
            ->get();

            return ['sectors'=>$sectors,'subCategories'=>$subCategories,'incubator'=>$incubator];
        }

        return false;
    }

    public function getAllActivities($secSlug)
    {
        $sector =  Sector::where('slug',$secSlug)
        ->first();

        if(isset($sector->id)){
            $secId = $sector->id;
            return Activity::select('id','slug','name','nameAr')
            ->where('sectorId', $secId)
            ->where('status',true)
            ->get();
        }

        return false;
    }

    public function getAllEntitiesAndSubActivities($actSlug)
    {
        $activity =  Activity::where('slug',$actSlug)
        ->first();

        if(isset($activity->id)){
            $actId = $activity->id;

            $entities = Entity::whereHas('activities', function ($query) use ($actId) {
                $query->where('activityId', $actId);
            })
            ->select('id','slug', 'name', 'nameAr')
            ->where('status',true)
            ->get();

            $subActivities =  SubActivity::select('id','slug','name','nameAr')
            ->where('activityId', $actId)
            ->where('status',true)
            ->get();

            return ['entities'=>$entities,'subActivities'=>$subActivities];
        }

        return false;
    }

    public function getAllActivitiesWithEntity($entSlug)
    {
        $activityIds = Entity::with('activities')
        ->where('slug',$entSlug)
        ->with('activities:id')
        ->first()
        ?->activities
        ->pluck('id')
        ->toArray();

        return $activityIds;
    }

    public function getFormFields($request)
    {
        $category = isset($request['category']) ? $request['category'] : '';
        $subCategory = isset($request['subCategory']) ? $request['subCategory'] : '';
        $sector = isset($request['sector']) ? $request['sector'] : '';
        $activity = isset($request['activity']) ? $request['activity'] : '';
        $subActivity = isset($request['subActivity']) ? $request['subActivity'] : '';
        $entity = isset($request['entity']) ? $request['entity'] : '';
        $incubator = isset($request['incubator']) ? $request['incubator'] : '';

        $formFields = FormFields::whereHas('formMetas', function ($q) use ($category,$subCategory,$sector,$activity,$subActivity,$entity,$incubator){
            $q->where(function ($query) use ($category,$subCategory,$sector,$activity,$subActivity,$entity){
                $query->where('catSlug',$category)
                ->where('subCatSlug',$subCategory)
                ->where('sectorSlug',$sector)
                ->where('activitySlug',$activity)
                ->where('subActivitySlug',$subActivity)
                ->where('entitySlug',$entity)
                ->where('incubatorSlug',NULL);
            })->orWhere(function ($query) use ($category,$subCategory,$sector,$activity,$subActivity,$incubator){
                $query->where('catSlug',$category)
                ->where('subCatSlug',$subCategory)
                ->where('sectorSlug',$sector)
                ->where('activitySlug',$activity)
                ->where('subActivitySlug',$subActivity)
                ->where('entitySlug',NULL)
                ->where('incubatorSlug',$incubator);
            })->orWhere(function ($query) use ($category,$subCategory,$sector,$activity,$subActivity){
                $query->where('catSlug',$category)
                ->where('subCatSlug',$subCategory)
                ->where('sectorSlug',$sector)
                ->where('activitySlug',$activity)
                ->where('subActivitySlug',$subActivity)
                ->where('entitySlug',NULL)
                ->where('incubatorSlug',NULL);
            })->orWhere(function ($query) use ($category,$subCategory,$sector,$activity){
                $query->where('catSlug',$category)
                ->where('subCatSlug',$subCategory)
                ->where('sectorSlug',$sector)
                ->where('activitySlug',$activity)
                ->where('subActivitySlug',NULL)
                ->where('entitySlug',NULL)
                ->where('incubatorSlug',NULL);
            })->orWhere(function ($query) use ($category,$subCategory,$sector){
                $query->where('catSlug',$category)
                ->where('subCatSlug',$subCategory)
                ->where('sectorSlug',$sector)
                ->where('activitySlug',NULL)
                ->where('subActivitySlug',NULL)
                ->where('entitySlug',NULL)
                ->where('incubatorSlug',NULL);
            })->orWhere(function ($query) use ($category,$subCategory){
                $query->where('catSlug',$category)
                ->where('subCatSlug',$subCategory)
                ->where('sectorSlug',NULL)
                ->where('activitySlug',NULL)
                ->where('subActivitySlug',NULL)
                ->where('entitySlug',NULL)
                ->where('incubatorSlug',NULL);
            })->orWhere(function ($query) use ($category){
                $query->where('catSlug',$category)
                ->where('subCatSlug',NULL)
                ->where('sectorSlug',NULL)
                ->where('activitySlug',NULL)
                ->where('subActivitySlug',NULL)
                ->where('entitySlug',NULL)
                ->where('incubatorSlug',NULL);
            });
        })->get();

        $formFields->map(function ($query){
            $query->meta = $query->meta ? json_decode($query->meta) : NULL;

            return $query;
        });

        return $formFields;
    }
}
