<?php

namespace App\Http\Controllers\Api\V1\Admin;


use Illuminate\Http\Request;


use App\Http\Requests\API\V1\Admin\CategoryCreateRequest;
use App\Http\Requests\API\V1\Admin\CategoryUpdateRequest;
use App\Http\Requests\API\V1\Admin\SubCategoryCreateRequest;
use App\Http\Requests\API\V1\Admin\SubCategoryUpdateRequest;
use App\Http\Requests\API\V1\Admin\SectorCreateRequest;
use App\Http\Requests\API\V1\Admin\SectorUpdateRequest;
use App\Http\Requests\API\V1\Admin\ActivityCreateRequest;
use App\Http\Requests\API\V1\Admin\ActivityUpdateRequest;
use App\Http\Requests\API\V1\Admin\SubActivityCreateRequest;
use App\Http\Requests\API\V1\Admin\SubActivityUpdateRequest;
use App\Http\Requests\API\V1\Admin\EntityCreateRequest;
use App\Http\Requests\API\V1\Admin\EntityUpdateRequest;
use App\Http\Requests\API\V1\Admin\IncubatorCreateRequest;
use App\Http\Requests\API\V1\Admin\IncubatorUpdateRequest;


use App\Http\Controllers\Api\BaseController;

use App\Services\V1\Admin\GenericService;


class GenericController extends BaseController
{
    public function __construct(private GenericService $service) {}

    // ===== CATEGORIES =====
    public function categories()
    {
        return response()->json($this->service->allCategories());
    }
    public function category($id)
    {
        return response()->json($this->service->findCategory($id));
    }
    public function createCategory(CategoryCreateRequest $request)
    {
        return response()->json($this->service->createCategory($request->all()), 201);
    }
    public function updateCategory(CategoryUpdateRequest $request, $id)
    {
        return response()->json($this->service->updateCategory($id, $request->all()));
    }
    public function deleteCategory($id)
    {
        $this->service->deleteCategory($id);
        return response()->json(['message' => 'Deleted']);
    }

    // ===== SUBCATEGORIES =====
    public function subCategories()
    {
        return response()->json($this->service->allSubCategories());
    }
    public function subCategory($id)
    {
        return response()->json($this->service->findSubCategory($id));
    }
    public function createSubCategory(SubCategoryCreateRequest $request)
    {
        return response()->json($this->service->createSubCategory($request->all()), 201);
    }
    public function updateSubCategory(SubCategoryUpdateRequest $request, $id)
    {
        return response()->json($this->service->updateSubCategory($id, $request->all()));
    }
    public function deleteSubCategory($id)
    {
        $this->service->deleteSubCategory($id);
        return response()->json(['message' => 'Deleted']);
    }

    // ===== SECTORS =====
    public function sectors()
    {
        return response()->json($this->service->allSectors());
    }
    public function sector($id)
    {
        return response()->json($this->service->findSector($id));
    }
    public function createSector(SectorCreateRequest $request)
    {
        return response()->json($this->service->createSector($request->all()), 201);
    }
    public function updateSector(SectorUpdateRequest $request, $id)
    {
        return response()->json($this->service->updateSector($id, $request->all()));
    }
    public function deleteSector($id)
    {
        $this->service->deleteSector($id);
        return response()->json(['message' => 'Deleted']);
    }
    public function attachCategoriesToSector(Request $request, $sectorId)
    {
        return response()->json($this->service->attachCategoriesToSector($sectorId, $request->categoryIds));
    }

    // ===== ACTIVITIES =====
    public function activities()
    {
        return response()->json($this->service->allActivities());
    }
    public function activity($id)
    {
        return response()->json($this->service->findActivity($id));
    }
    public function createActivity(ActivityCreateRequest $request)
    {
        return response()->json($this->service->createActivity($request->all()), 201);
    }
    public function updateActivity(ActivityUpdateRequest $request, $id)
    {
        return response()->json($this->service->updateActivity($id, $request->all()));
    }
    public function deleteActivity($id)
    {
        $this->service->deleteActivity($id);
        return response()->json(['message' => 'Deleted']);
    }
    public function attachEntitiesToActivity(Request $request, $activityId)
    {
        return response()->json($this->service->attachEntitiesToActivity($activityId, $request->entityIds));
    }

    // ===== SUBACTIVITIES =====
    public function subActivities()
    {
        return response()->json($this->service->allSubActivities());
    }
    public function subActivity($id)
    {
        return response()->json($this->service->findSubActivity($id));
    }
    public function createSubActivity(SubActivityCreateRequest $request)
    {
        return response()->json($this->service->createSubActivity($request->all()), 201);
    }
    public function updateSubActivity(SubActivityUpdateRequest $request, $id)
    {
        return response()->json($this->service->updateSubActivity($id, $request->all()));
    }
    public function deleteSubActivity($id)
    {
        $this->service->deleteSubActivity($id);
        return response()->json(['message' => 'Deleted']);
    }

    // ===== ENTITIES =====
    public function entities()
    {
        return response()->json($this->service->allEntities());
    }
    public function entity($id)
    {
        return response()->json($this->service->findEntity($id));
    }
    public function createEntity(EntityCreateRequest $request)
    {
        return response()->json($this->service->createEntity($request->all()), 201);
    }
    public function updateEntity(EntityUpdateRequest $request, $id)
    {
        return response()->json($this->service->updateEntity($id, $request->all()));
    }
    public function deleteEntity($id)
    {
        $this->service->deleteEntity($id);
        return response()->json(['message' => 'Deleted']);
    }

    // ===== INCUBATORS =====
    public function incubators()
    {
        return response()->json($this->service->allIncubators());
    }
    public function incubator($id)
    {
        return response()->json($this->service->findIncubator($id));
    }
    public function createIncubator(IncubatorCreateRequest $request)
    {
        return response()->json($this->service->createIncubator($request->all()), 201);
    }
    public function updateIncubator(IncubatorUpdateRequest $request, $id)
    {
        return response()->json($this->service->updateIncubator($id, $request->all()));
    }
    public function deleteIncubator($id)
    {
        $this->service->deleteIncubator($id);
        return response()->json(['message' => 'Deleted']);
    }
}
