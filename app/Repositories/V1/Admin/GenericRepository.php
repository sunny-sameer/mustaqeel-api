<?php

namespace App\Repositories\V1\Admin;


use App\Repositories\V1\Core\CoreRepository;


use App\Models\Categories as Category;
use App\Models\SubCategories as SubCategory;
use App\Models\Sectors as Sector;
use App\Models\Activities as Activity;
use App\Models\SubActivities as SubActivity;
use App\Models\Entities as Entity;
use App\Models\FormFieldMeta;
use App\Models\FormFields;
use App\Models\Incubator;
use App\Models\Nationality;
use App\Models\Stages;
use App\Models\StagesStatuses;
use Illuminate\Support\Arr;
use App\DTOs\V1\Requests\FormFieldsDTO;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Models\Categories;
use App\Models\SubCategories;
use App\Models\Sectors;
use App\Models\Activities;
use App\Models\SubActivities;
use App\Models\Entities;

class GenericRepository extends CoreRepository implements GenericInterface
{
    protected $categories;
    protected $subCategories;
    protected $sectors;
    protected $activities;
    protected $subActivities;
    protected $entities;
    protected $incubators;
    protected $nationality;
    protected $formFields;
    protected $stages;
    protected $stagesStatuses;

    public function __construct(
        Category $model,
        Categories $categories,
        SubCategories $subCategories,
        Sectors $sectors,
        Activities $activities,
        SubActivities $subActivities,
        Entities $entities,
        Incubator $incubators,
        Nationality $nationality,
        FormFields $formFields,
        Stages $stages,
        StagesStatuses $stagesStatuses
    ) {

        parent::__construct($model);
        $this->categories = $categories;
        $this->subCategories = $subCategories;
        $this->sectors = $sectors;
        $this->activities = $activities;
        $this->subActivities = $subActivities;
        $this->entities = $entities;
        $this->incubators = $incubators;
        $this->nationality = $nationality;
        $this->formFields = $formFields;
        $this->stages = $stages;
        $this->stagesStatuses = $stagesStatuses;
    }
    // ===== CATEGORIES =====
    public function allCategories($request)
    {
        $paginate = isset($request['perPage']) ? $request['perPage'] : 10;
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
    public function allSubCategories($request)
    {
        $paginate = isset($request['perPage']) ? $request['perPage'] : 10;
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
    public function allSectors($request)
    {
        $paginate = isset($request['perPage']) ? $request['perPage'] : 10;
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
    public function allActivities($request)
    {
        $paginate = isset($request['perPage']) ? $request['perPage'] : 10;
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
    public function allSubActivities($request)
    {
        $paginate = isset($request['perPage']) ? $request['perPage'] : 10;
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
    public function allEntities($request)
    {
        $paginate = isset($request['perPage']) ? $request['perPage'] : 10;
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
        if (isset($data['activityIds'])) $entity->activities()->sync([]);
        $entity->activities()->sync($data['activityIds']);

        return $this->findEntity($entity->id);
    }
    public function deleteEntity($id)
    {
        return Entity::findOrFail($id)->delete();
    }

    // ===== INCUBATORS =====
    public function allIncubators($request)
    {
        $paginate = isset($request['perPage']) ? $request['perPage'] : 10;
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
    public function allFormFields($request)
    {
        $paginate = isset($request['perPage']) ? $request['perPage'] : 10;

        $query = FormFields::with(['formMetas']);

        if (!empty($request['section'])) {
            $query->where('section', $request['section']);
        }

        if (!empty($request['group'])) {
            $query->where('group', $request['group']);
        }

        if (!empty($request['type'])) {
            $query->where('type', $request['type']);
        }

        if (isset($request['status'])) {
            $query->where('status', $request['status']);
        }

        // Check if custom sort is requested
        if (isset($request['sort_field']) && isset($request['sort_order'])) {
            $query->orderBy($request['sort_field'], $request['sort_order']);
        } else {
            // Default sorting: newest first (by created_at descending)
            $query->orderBy('created_at', 'desc');
        }

        $ff = $query->paginate($paginate);

        // Transform the data
        $ff->getCollection()->transform(function ($query) {
            $query->meta = $query->meta ? json_decode($query->meta) : null;
            if ($query->formMetas) {
                $query->formMetas->map(function ($meta) {
                    $meta->value = $meta->value ? json_decode($meta->value) : null;
                    return $meta;
                });
            }
            return $query;
        });

        return $ff;
    }

    public function findFormField($id)
    {
        $ff = FormFields::with(['formMetas'])->findOrFail($id);

        $ff->meta = $ff->meta ? json_decode($ff->meta) : null;
        if ($ff->formMetas) {
            $ff->formMetas->map(function ($meta) {
                $meta->value = $meta->value ? json_decode($meta->value) : null;
                return $meta;
            });
        }

        return $ff;
    }
    public function createFormField($data)
    {
        return DB::transaction(function () use ($data) {
            // Create form field
            $dto = FormFieldsDTO::fromRequest($data);
            $formFieldData = $dto->toArray();

            // Ensure unique slug
            $formFieldData['slug'] = $this->generateUniqueSlug($formFieldData['slug']);

            $ff = FormFields::create($formFieldData);

            // Save category rules if any
            if (!empty($dto->categoryRules)) {
                $this->saveCategoryRules($ff->id, $dto->categoryRules);
            }

            // Clear cache
            $this->clearFormCache();

            return $this->findFormField($ff->id);
        });
    }


    public function updateFormField($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $ff = FormFields::findOrFail($id);

            // Update form field
            $dto = FormFieldsDTO::fromRequest($data);
            $formFieldData = $dto->toArray();

            // Check if slug needs to be updated
            if ($formFieldData['slug'] !== $ff->slug) {
                $formFieldData['slug'] = $this->generateUniqueSlug($formFieldData['slug'], $id);
            }

            $ff->update($formFieldData);

            // Update category rules if provided
            if (isset($dto->categoryRules)) {
                // Delete existing rules
                $ff->formMetas()->delete();

                // Save new rules
                if (!empty($dto->categoryRules)) {
                    $this->saveCategoryRules($ff->id, $dto->categoryRules);
                }
            }

            // Clear cache
            $this->clearFormCache();

            return $this->findFormField($ff->id);
        });
    }

    public function deleteFormField($id)
    {
        return DB::transaction(function () use ($id) {
            $ff = FormFields::findOrFail($id);
            $ff->formMetas()->delete();
            return $ff->delete();
        });
    }

    public function updateOrCreateFormFieldMetaData($data, $formFieldId)
    {
        return DB::transaction(function () use ($data, $formFieldId) {
            $ff = FormFields::findOrFail($formFieldId);

            // Delete all existing metas
            $ff->formMetas()->delete();

            $meta = [];
            foreach ($data as $key => $value) {
                // Check if value is array and needs encoding
                if (is_array($value['value'])) {
                    $value['value'] = json_encode($value['value']);
                }

                $ffm = FormFieldMeta::withTrashed()
                    ->where(['ffId' => $formFieldId, 'key' => $value['key']])
                    ->first();

                if (isset($ffm->id)) {
                    if ($ffm->trashed()) {
                        $ffm->restore();
                    }
                    $ffm->update($value);
                } else {
                    $ffm = FormFieldMeta::create($value);
                }

                // Decode value for response
                $ffm->value = json_decode($ffm->value);
                $meta[] = $ffm;
            }

            // Clear cache
            $this->clearFormCache();

            return $meta;
        });
    }

    public function getFormStructure(array $params): array
    {
        $cacheKey = 'form_structure_' . md5(json_encode($params));

        return Cache::remember($cacheKey, 3600, function () use ($params) {
            // Get all active fields with their metas
            $fields = FormFields::with('formMetas')
                ->where('status', true)
                ->orderBy('section')
                ->orderBy('group')
                ->orderBy('fieldOrder')
                ->get();

            // Decode JSON data
            $fields->map(function ($field) {
                $field->meta = $field->meta ? json_decode($field->meta, true) : [];
                $field->conditions = $field->conditions ? json_decode($field->conditions, true) : null;

                if ($field->formMetas) {
                    $field->formMetas->map(function ($meta) {
                        $meta->value = $meta->value ? json_decode($meta->value, true) : [];
                        return $meta;
                    });
                }
                return $field;
            });

            // Filter fields based on category rules
            $filteredFields = $fields->filter(function ($field) use ($params) {
                return $this->matchesCategoryRules($field, $params);
            });

            // Group by section and group
            $structure = [];

            foreach ($filteredFields as $field) {
                $section = $field->section;
                $group = $field->group;

                if (!isset($structure[$section])) {
                    $structure[$section] = [
                        'key' => $section,
                        'name' => $this->getSectionName($section),
                        'groups' => []
                    ];
                }

                if (!isset($structure[$section]['groups'][$group])) {
                    $structure[$section]['groups'][$group] = [
                        'key' => $group,
                        'name' => $this->getGroupName($group),
                        'repeatable' => $field->repeatable,
                        'repeatableLabel' => $field->repeatableLabel,
                        'repeatableMax' => $field->repeatableMax,
                        'fields' => []
                    ];
                }

                // Check if field should be required for this category
                $isRequired = $this->isFieldRequired($field, $params);

                $fieldData = [
                    'id' => $field->id,
                    'nameEn' => $field->nameEn,
                    'nameAr' => $field->nameAr,
                    'slug' => $field->slug,
                    'type' => $field->type,
                    'meta' => $field->meta,
                    'gridColumns' => $field->gridColumns,
                    'isRequired' => $isRequired,
                    'conditions' => $field->conditions,
                ];

                // For group type, include nested fields
                if ($field->type === 'group' && isset($field->meta['fields'])) {
                    $fieldData['fields'] = $field->meta['fields'];
                }

                $structure[$section]['groups'][$group]['fields'][] = $fieldData;
            }

            // Define custom section order
            $sectionOrder = [
                'personalInfo',
                'employmentAndEducation',
                'ResidencyAndTravelAndFamily',
                'documents'
            ];

            // Sort sections by custom order
            uksort($structure, function ($a, $b) use ($sectionOrder) {
                $posA = array_search($a, $sectionOrder);
                $posB = array_search($b, $sectionOrder);

                if ($posA === false) $posA = count($sectionOrder);
                if ($posB === false) $posB = count($sectionOrder);

                return $posA - $posB;
            });

            // Reformat and sort groups within each section
            $result = [];
            foreach ($structure as $sectionKey => $sectionData) {
                // Get group order for this section
                $groupOrder = $this->getGroupOrder($sectionKey);

                if (!empty($groupOrder)) {
                    // Sort groups by defined order
                    uksort($sectionData['groups'], function ($a, $b) use ($groupOrder) {
                        $posA = array_search($a, $groupOrder);
                        $posB = array_search($b, $groupOrder);

                        if ($posA === false) $posA = count($groupOrder);
                        if ($posB === false) $posB = count($groupOrder);

                        return $posA - $posB;
                    });
                }

                $sectionData['groups'] = array_values($sectionData['groups']);
                $result[] = $sectionData;
            }

            return $result;
        });
    }

    private function getGroupOrder(string $section): array
    {
        return match ($section) {
            'personalInfo' => [
                'applicantInfo',
                'contactInfo',
                'passportDetails'
            ],
            'employmentAndEducation' => [
                'educations',
                'previousJobs',
                'employmentDetails',
                'investmentDetails'
            ],
            'ResidencyAndTravelAndFamily' => [
                'residencyDetails',
                'residences',
                'otherNationalities',
                'countriesVisitedLast10Years',
                'familyMembers'
            ],
            'documents' => [
                'requiredDocuments',
                'residencyDocuments',
                'investmentDocuments',
                'ictMinistryDocuments'
            ],
            default => []
        };
    }

    private function saveCategoryRules(int $formFieldId, array $rules): void
    {
        foreach ($rules as $rule) {
            // Build value array with hierarchy
            $value = [];

            if (isset($rule['subCategorySlug']) && !empty($rule['subCategorySlug'])) {
                $value['sub_category'] = $rule['subCategorySlug'];
            }
            if (isset($rule['sectorSlug']) && !empty($rule['sectorSlug'])) {
                $value['sector'] = $rule['sectorSlug'];
            }
            if (isset($rule['activitySlug']) && !empty($rule['activitySlug'])) {
                $value['activity'] = $rule['activitySlug'];
            }
            if (isset($rule['subActivitySlug']) && !empty($rule['subActivitySlug'])) {
                $value['sub_activity'] = $rule['subActivitySlug'];
            }
            if (isset($rule['entitySlug']) && !empty($rule['entitySlug'])) {
                $value['entity'] = $rule['entitySlug'];
            }
            if (isset($rule['incubatorSlug']) && !empty($rule['incubatorSlug'])) {
                $value['incubator'] = $rule['incubatorSlug'];
            }

            $metaData = [
                'ffId' => $formFieldId,
                'key' => $rule['categorySlug'] ?? 'all',
                'value' => json_encode($value, JSON_UNESCAPED_UNICODE),
                'onshoreOffShore' => $rule['onshoreOffShore'] ?? 'both',
                'isRequired' => $rule['isRequired'] ?? false,
            ];

            FormFieldMeta::create($metaData);
        }
    }

    /**
     * Check if field matches category rules
     */
    private function matchesCategoryRules($field, array $params): bool
    {
        $metas = $field->formMetas;

        if ($metas->isEmpty()) {
            return true; // No rules means show for all
        }

        foreach ($metas as $meta) {
            if ($this->ruleMatches($meta, $params)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a specific rule matches
     */
    private function ruleMatches($meta, array $params): bool
    {
        // Check category
        if ($meta->key !== 'all' && $meta->key !== ($params['category'] ?? null)) {
            return false;
        }

        $value = $meta->value;

        // Check sub category
        if (!empty($value['subCategory']) && $value['subCategory'] !== ($params['subCategory'] ?? null)) {
            return false;
        }

        // Check sector
        if (!empty($value['sector']) && $value['sector'] !== ($params['sector'] ?? null)) {
            return false;
        }

        // Check activity
        if (!empty($value['activity']) && $value['activity'] !== ($params['activity'] ?? null)) {
            return false;
        }

        // Check sub activity
        if (!empty($value['subActivity']) && $value['subActivity'] !== ($params['subActivity'] ?? null)) {
            return false;
        }

        // Check entity
        if (!empty($value['entity']) && $value['entity'] !== ($params['entity'] ?? null)) {
            return false;
        }

        // Check incubator
        if (!empty($value['incubator']) && $value['incubator'] !== ($params['incubator'] ?? null)) {
            return false;
        }

        // Check onshore/offshore
        if ($meta->onshoreOffShore !== 'both' && $meta->onshoreOffShore !== ($params['onshoreOffShore'] ?? 'both')) {
            return false;
        }

        return true;
    }

    /**
     * Check if field is required for current category
     */
    private function isFieldRequired($field, array $params): bool
    {
        // If no params provided, return false
        if (empty($params)) {
            return false;
        }

        foreach ($field->formMetas as $meta) {
            if ($this->ruleMatches($meta, $params)) {
                return (bool) $meta->isRequired;
            }
        }

        return false;
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $originalSlug = $slug;
        $count = 1;

        while (true) {
            $query = FormFields::where('slug', $slug);

            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Get section name translations
     */
    private function getSectionName(string $section): array
    {
        return match ($section) {
            'personalInfo' => [
                'en' => 'Personal Information',
                'ar' => 'المعلومات الشخصية'
            ],
            'employmentAndEducation' => [
                'en' => 'Employment & Education',
                'ar' => 'التوظيف والتعليم'
            ],
            'ResidencyAndTravelAndFamily' => [
                'en' => 'Residency, Travel & Family',
                'ar' => 'الإقامة والسفر والعائلة'
            ],
            'documents' => [
                'en' => 'Document Upload',
                'ar' => 'رفع المستندات'
            ],
            default => [
                'en' => ucfirst(str_replace('-', ' ', $section)),
                'ar' => $section
            ]
        };
    }

    /**
     * Get group name translations
     */
    private function getGroupName(string $group): array
    {
        return match ($group) {
            'identificationData' => [
                'en' => 'Identification Data',
                'ar' => 'بيانات التعريف'
            ],
            'applicantInfo' => [
                'en' => 'Applicant Information',
                'ar' => 'معلومات مقدم الطلب'
            ],
            'contactInfo' => [
                'en' => 'Contact Information',
                'ar' => 'معلومات الاتصال'
            ],
            'passportDetails' => [
                'en' => 'Passport Details',
                'ar' => 'تفاصيل جواز السفر'
            ],
            'employmentDetails' => [
                'en' => 'Employment Details',
                'ar' => 'تفاصيل التوظيف'
            ],
            'previousJobs' => [
                'en' => 'Previous Jobs',
                'ar' => 'الوظائف السابقة'
            ],
            'educations' => [
                'en' => 'Educations',
                'ar' => 'التعليم'
            ],
            'residences' => [
                'en' => 'Residences',
                'ar' => 'الإقامات'
            ],
            'otherNationalities' => [
                'en' => 'Other Nationalities',
                'ar' => 'الجنسيات الأخرى'
            ],
            'countriesVisitedLast10Years' => [
                'en' => 'Countries Visited',
                'ar' => 'الدول التي تمت زيارتها'
            ],
            'familyMembers' => [
                'en' => 'Family Members',
                'ar' => 'أفراد العائلة'
            ],
            'requiredDocuments' => [
                'en' => 'Required Documents',
                'ar' => 'المستندات المطلوبة'
            ],
            'residencyDocuments' => [ // ADD THIS
                'en' => 'Residency Documents',
                'ar' => 'مستندات الإقامة'
            ],
            'investmentDocuments' => [ // ADD THIS if missing
                'en' => 'Investment Documents',
                'ar' => 'مستندات الاستثمار'
            ],
            'ictMinistryDocuments' => [ // ADD THIS if missing
                'en' => 'ICT Ministry Documents',
                'ar' => 'مستندات وزارة الاتصالات'
            ],
            'residencyDetails' => [ // ADD THIS
                'en' => 'Residency Details',
                'ar' => 'تفاصيل الإقامة'
            ],
            default => [
                'en' => ucfirst(str_replace('-', ' ', $group)),
                'ar' => $group
            ]
        };
    }
    /**
     * Clear form structure cache
     */
    private function clearFormCache(): void
    {
        // In production, you might want to be more selective
        // For now, we'll just clear by prefix pattern
        $cache = app('cache');
        if (method_exists($cache->store(), 'getPrefix')) {
            // For file/database cache
            $prefix = $cache->store()->getPrefix() . 'form_structure_*';
            // You'd need to implement a cache clear by pattern
        }
    }


    // ===== STAGES =====
    public function allStages($request)
    {
        $paginate = isset($request['perPage']) ? $request['perPage'] : 10;
        return Stages::orderBy('order', 'ASC')->paginate($paginate);
    }
    public function findStage($id)
    {
        return Stages::findOrFail($id);
    }
    public function createStage($data)
    {
        $stage = Stages::create($data);

        return $this->findStage($stage->id);
    }
    public function updateStage($id, $data)
    {
        $stage = Stages::findOrFail($id);
        $stage->update($data);

        return $this->findStage($stage->id);
    }
    public function deleteStage($id)
    {
        return Stages::findOrFail($id)->delete();
    }

    // ===== STAGE STATUSES =====
    public function allStageStatuses($request)
    {
        $paginate = isset($request['perPage']) ? $request['perPage'] : 10;
        return StagesStatuses::with('stage')->paginate($paginate);
    }
    public function findStageStatus($id)
    {
        return StagesStatuses::with('stage')->findOrFail($id);
    }
    public function createStageStatus($data)
    {
        $stageStatus = StagesStatuses::create($data);

        return $this->findStageStatus($stageStatus->id);
    }
    public function updateStageStatus($id, $data)
    {
        $stageStatus = StagesStatuses::findOrFail($id);
        $stageStatus->update($data);

        return $this->findStageStatus($stageStatus->id);
    }
    public function deleteStageStatus($id)
    {
        return StagesStatuses::findOrFail($id)->delete();
    }
    public function getStatusWithStage($stageSlug, $status)
    {
        $stageStatus = StagesStatuses::whereHas('stage', function ($q) use ($stageSlug) {
            $q->where('slug', $stageSlug);
        })->where('name', $status)->first();

        if (isset($stageStatus->slug)) {
            $slug = explode('-', $stageStatus->slug);
            return $slug[0];
        }
        return null;
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
        return Nationality::select('name', 'phonecode')->get();
    }

    public function getAllCategories()
    {
        return Category::select('id', 'slug', 'name', 'nameAr')
            ->where('status', true)
            ->get();
    }

    public function getAllSectorsSubCategoriesAndIncubators($catSlug)
    {
        $category =  Category::where('slug', $catSlug)
            ->first();

        if (isset($category->id)) {
            $catId = $category->id;
            $sectors = Sector::whereHas('categories', function ($query) use ($catId) {
                $query->where('categoryId', $catId);
            })
                ->select('id', 'slug', 'name', 'nameAr')
                ->where('status', true)
                ->get();

            $subCategories =  SubCategory::select('id', 'slug', 'name', 'nameAr')
                ->where('categoryId', $catId)
                ->where('status', true)
                ->get();

            $incubator =  Incubator::select('id', 'slug', 'name', 'nameAr')
                ->where('categoryId', $catId)
                ->where('status', true)
                ->get();

            return ['sectors' => $sectors, 'subCategories' => $subCategories, 'incubator' => $incubator];
        }

        return false;
    }

    public function getAllActivities($secSlug)
    {
        $sector =  Sector::where('slug', $secSlug)
            ->first();

        if (isset($sector->id)) {
            $secId = $sector->id;
            return Activity::select('id', 'slug', 'name', 'nameAr')
                ->where('sectorId', $secId)
                ->where('status', true)
                ->get();
        }

        return false;
    }

    public function getAllEntitiesAndSubActivities($actSlug)
    {
        $activity =  Activity::where('slug', $actSlug)
            ->first();

        if (isset($activity->id)) {
            $actId = $activity->id;

            $entities = Entity::whereHas('activities', function ($query) use ($actId) {
                $query->where('activityId', $actId);
            })
                ->select('id', 'slug', 'name', 'nameAr')
                ->where('status', true)
                ->get();

            $subActivities =  SubActivity::select('id', 'slug', 'name', 'nameAr')
                ->where('activityId', $actId)
                ->where('status', true)
                ->get();

            return ['entities' => $entities, 'subActivities' => $subActivities];
        }

        return false;
    }

    public function getAllActivitiesWithEntity($entSlug)
    {
        $activityIds = Entity::with('activities')
            ->where('slug', $entSlug)
            ->with('activities:id')
            ->first()
            ?->activities
            ->pluck('id')
            ->toArray();

        return $activityIds;
    }

    public function getFormFields($data)
    {
        $category = $data['category'] ?? null;

        if (!$category) {
            return collect([]);
        }

        // Get form fields with their meta data
        $query = $this->formFields->where('status', 1)
            ->with(['formMetas' => function ($query) use ($data) {
                $query->where('key', $data['category'] ?? null);
            }]);

        // Apply additional filters if needed based on other identification data
        $formFields = $query->orderBy('section')
            ->orderBy('group')
            ->orderBy('fieldOrder')
            ->get();

        return $formFields;
    }

    


    public function getSingleFormField($type, $category = null)
    {
        $ff = FormFields::whereHas('formMetas')->where('slug', $type);
        if (!empty($category)) {
            $ff = $ff->whereHas('formMetas', function ($q) use ($category) {
                $q->where('key', $category);
            });
        }
        $ff = $ff->first();

        if (isset($ff->meta)) {
            $ff->extensions = '';
            $ff->meta = $ff->meta ? json_decode($ff->meta) : NULL;
            if (isset($ff->meta->extensions)) {
                $ff->extensions = implode(',', $ff->meta->extensions);
            }
            $ff->formMetas->value = $ff->formMetas->value ? json_decode($ff->formMetas->value) : NULL;
        }

        return $ff;
    }
}
