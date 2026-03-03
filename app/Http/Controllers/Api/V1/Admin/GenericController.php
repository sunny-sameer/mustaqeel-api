<?php

namespace App\Http\Controllers\API\V1\Admin;


use Illuminate\Http\Request;


use App\Http\Controllers\Api\BaseController;


use App\Services\V1\Admin\GenericService;


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
use App\Http\Requests\API\V1\Admin\FormFieldCreateRequest;
use App\Http\Requests\API\V1\Admin\FormFieldUpdateRequest;
use App\Http\Requests\API\V1\Admin\AttachCategoryToSectorRequest;
use App\Http\Requests\API\V1\Admin\AttachEntityOfActivityRequest;
use App\Http\Requests\API\V1\Admin\AttachActivityOfEntityRequest;
use App\Http\Requests\API\V1\Admin\StageCreateRequest;
use App\Http\Requests\API\V1\Admin\StageUpdateRequest;
use App\Http\Requests\API\V1\Admin\StageStatusCreateRequest;
use App\Http\Requests\API\V1\Admin\StageStatusUpdateRequest;

use Illuminate\Http\JsonResponse;


class GenericController extends BaseController
{
    /**
     * See Swagger annotations in \App\Swaggers\V1\Admin\GenericSwagger
     */

    public function __construct(private GenericService $service) {}

    // ===== CATEGORIES =====
    public function categories(Request $request)
    {
        return response()->json($this->service->allCategories($request->all()));
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
    public function subCategories(Request $request)
    {
        return response()->json($this->service->allSubCategories($request->all()));
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
    public function sectors(Request $request)
    {
        return response()->json($this->service->allSectors($request->all()));
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
    public function attachCategoriesToSector(AttachCategoryToSectorRequest $request, $sectorId)
    {
        return response()->json($this->service->attachCategoriesToSector($sectorId, $request->categoryIds));
    }

    // ===== ACTIVITIES =====
    public function activities(Request $request)
    {
        return response()->json($this->service->allActivities($request->all()));
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
    public function attachEntitiesToActivity(AttachEntityOfActivityRequest $request, $activityId)
    {
        return response()->json($this->service->attachEntitiesToActivity($activityId, $request->entityIds));
    }

    // ===== SUBACTIVITIES =====
    public function subActivities(Request $request)
    {
        return response()->json($this->service->allSubActivities($request->all()));
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
    public function entities(Request $request)
    {
        return response()->json($this->service->allEntities($request->all()));
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
    public function attachActivitiesToEntity(AttachActivityOfEntityRequest $request, $entityId)
    {
        return response()->json($this->service->attachActivitiesToEntity($entityId, $request->activityIds));
    }

    // ===== INCUBATORS =====
    public function incubators(Request $request)
    {
        return response()->json($this->service->allIncubators($request->all()));
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

    // ===== FORM FIELDS =====
    public function formFields(Request $request): JsonResponse
    {
        $fields = $this->service->allFormFields($request->all());
        return response()->json(['success' => true, 'data' => $fields]);
    }
    public function formField(int $id): JsonResponse
    {
        $field = $this->service->findFormField($id);

        if (!$field) {
            return response()->json([
                'success' => false,
                'message' => 'Form field not found'
            ], 404);
        }
        return response()->json(['success' => true, 'data' => $field]);
    }
    public function createFormField(FormFieldCreateRequest $request): JsonResponse
    {
        try {
            $field = $this->service->createFormField($request);
            return response()->json([
                'success' => true,
                'message' => 'Form field created successfully',
                'data' => $field
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create form field',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function updateFormField(FormFieldUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $field = $this->service->updateFormField($id, $request);

            return response()->json([
                'success' => true,
                'message' => 'Form field updated successfully',
                'data' => $field
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update form field',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function deleteFormField(int $id): JsonResponse
    {
        try {
            $this->service->deleteFormField($id);

            return response()->json(['success' => true, 'message' => 'Form field deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete form field',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Get form structure for frontend
     */
    public function formStructure(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'category' => 'required|string|exists:categories,slug',
                'sub_category' => 'nullable|string|exists:sub_categories,slug',
                'sector' => 'nullable|string|exists:sectors,slug',
                'activity' => 'nullable|string|exists:activities,slug',
                'sub_activity' => 'nullable|string|exists:sub_activities,slug',
                'entity' => 'nullable|string|exists:entities,slug',
                'incubator' => 'nullable|string|exists:incubators,slug',
                'onshore_offshore' => 'nullable|in:onshore,offshore,both'
            ]);

            $structure = $this->service->getFormStructure($validated);

            return response()->json([
                'success' => true,
                'data' => $structure
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get field types
     */
    public function types(): JsonResponse
    {
        $types = [
            ['value' => 'text', 'label' => 'Text Input', 'hasOptions' => false],
            ['value' => 'textarea', 'label' => 'Text Area', 'hasOptions' => false],
            ['value' => 'select', 'label' => 'Dropdown Select', 'hasOptions' => true],
            ['value' => 'radio', 'label' => 'Radio Buttons', 'hasOptions' => true],
            ['value' => 'checkbox', 'label' => 'Checkboxes', 'hasOptions' => true],
            ['value' => 'file', 'label' => 'File Upload', 'hasOptions' => false],
            ['value' => 'date', 'label' => 'Date Picker', 'hasOptions' => false],
            ['value' => 'email', 'label' => 'Email Input', 'hasOptions' => false],
            ['value' => 'number', 'label' => 'Number Input', 'hasOptions' => false],
            ['value' => 'group', 'label' => 'Group (Repeatable)', 'hasOptions' => false],
        ];
        return response()->json(['success' => true, 'data' => $types]);
    }

    /**
     * Get available sections
     */
    public function sections(): JsonResponse
    {
        $sections = [
            ['value' => 'personal-info', 'label' => 'Personal Information'],
            ['value' => 'employment-education', 'label' => 'Employment & Education'],
            ['value' => 'residency-travel', 'label' => 'Residency, Travel & Family'],
            ['value' => 'documents', 'label' => 'Document Upload'],
        ];
        return response()->json(['success' => true, 'data' => $sections]);
    }

    // ===== STAGES =====
    public function stages(Request $request)
    {
        return response()->json($this->service->allStages($request->all()));
    }
    public function stage($id)
    {
        return response()->json($this->service->findStage($id));
    }
    public function createStage(StageCreateRequest $request)
    {
        return response()->json($this->service->createStage($request->all()), 201);
    }
    public function updateStage(StageUpdateRequest $request, $id)
    {
        return response()->json($this->service->updateStage($id, $request->all()));
    }
    public function deleteStage($id)
    {
        $this->service->deleteStage($id);
        return response()->json(['message' => 'Deleted']);
    }

    // ===== STAGE STATUSES =====
    public function stageStatuses(Request $request)
    {
        return response()->json($this->service->allStageStatuses($request->all()));
    }
    public function stageStatus($id)
    {
        return response()->json($this->service->findStageStatus($id));
    }
    public function createStageStatus(StageStatusCreateRequest $request)
    {
        return response()->json($this->service->createStageStatus($request->all()), 201);
    }
    public function updateStageStatus(StageStatusUpdateRequest $request, $id)
    {
        return response()->json($this->service->updateStageStatus($id, $request->all()));
    }
    public function deleteStageStatus($id)
    {
        $this->service->deleteStageStatus($id);
        return response()->json(['message' => 'Deleted']);
    }
}
