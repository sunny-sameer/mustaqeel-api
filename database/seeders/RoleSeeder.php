<?php

namespace Database\Seeders;

use App\Models\RoleLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name'=>'admin',
                'type'=>'jusour',
                'approval_levels'=>2,
                'level' => [
                    [
                        'name' => 'super-admin',
                        'level' => 2
                    ],
                    [
                        'name' => 'manager',
                        'level' => 2
                    ],
                    [
                        'name' => 'junior',
                        'level' => 1
                    ]
                ]
            ],
            [
                'name'=>'applicant',
                'type'=>'applicant',
                'approval_levels'=>0
            ],
            [
                'name'=>'investor',
                'type'=>'applicant',
                'approval_levels'=>0
            ],
            [
                'name'=>'entity',
                'type'=>'entity',
                'approval_levels'=>3,
                'level' => [
                    [
                        'name' => 'officer',
                        'level' => 1
                    ],
                    [
                        'name' => 'supervisor',
                        'level' => 2
                    ],
                    [
                        'name' => 'manager',
                        'level' => 3
                    ]
                ]
            ],
            [
                'name'=>'incubator',
                'type'=>'entity',
                'approval_levels'=>1,
                'level' => [
                    [
                        'name' => 'manager',
                        'level' => 1
                    ]
                ]
            ],
            [
                'name'=>'moci',
                'type'=>'entity',
                'approval_levels'=>1,
                'level' => [
                    [
                        'name' => 'manager',
                        'level' => 1
                    ]
                ]
            ]
        ];

        foreach ($roles as $role) {
            $data = [
                'name'=>$role['name'],
                'type'=>$role['type'],
                'approval_levels'=>$role['approval_levels'],
            ];
            $roleData = Role::where(['name'=>$data['name'],'guard_name'=>'web','type'=>$data['type'],'approval_levels'=>$data['approval_levels']])->first();
            if(empty($roleData)){
                $roleData = Role::create($data);
                if(isset($role['level'])){
                    foreach ($role['level'] as $key => $value) {
                        $value['role_id'] = $roleData->id;
                        RoleLevel::create($value);
                    }
                }

            }
            if($roleData->name == 'admin'){
                $roleData->givePermissionTo([]);
                $roleData->givePermissionTo([
                    'view-talent-applications',
                    'show-talent-applications',
                    'create-talent-applications',
                    'edit-talent-applications',
                    'delete-talent-applications',
                    'under-review-talent-applications',
                    'on-hold-talent-applications',
                    'approve-talent-applications',
                    'reject-talent-applications',
                    'cancel-talent-applications',
                    'generate-pdf-talent-applications',
                    'generate-excel-talent-applications',
                    'export-data-talent-applications',
                    'view-entrepreneur-applications',
                    'show-entrepreneur-applications',
                    'create-entrepreneur-applications',
                    'edit-entrepreneur-applications',
                    'delete-entrepreneur-applications',
                    'under-review-entrepreneur-applications',
                    'on-hold-entrepreneur-applications',
                    'approve-entrepreneur-applications',
                    'reject-entrepreneur-applications',
                    'cancel-entrepreneur-applications',
                    'generate-pdf-entrepreneur-applications',
                    'generate-excel-entrepreneur-applications',
                    'export-data-entrepreneur-applications',
                    'view-investor-applications',
                    'show-investor-applications',
                    'create-investor-applications',
                    'edit-investor-applications',
                    'delete-investor-applications',
                    'under-review-investor-applications',
                    'on-hold-investor-applications',
                    'approve-investor-applications',
                    'reject-investor-applications',
                    'cancel-investor-applications',
                    'generate-pdf-investor-applications',
                    'generate-excel-investor-applications',
                    'export-data-investor-applications',
                    'view-executive-applications',
                    'show-executive-applications',
                    'create-executive-applications',
                    'edit-executive-applications',
                    'delete-executive-applications',
                    'under-review-executive-applications',
                    'on-hold-executive-applications',
                    'approve-executive-applications',
                    'reject-executive-applications',
                    'cancel-executive-applications',
                    'generate-pdf-executive-applications',
                    'generate-excel-executive-applications',
                    'export-data-executive-applications',
                    'view-categories',
                    'show-categories',
                    'create-categories',
                    'edit-categories',
                    'delete-categories',
                    'view-sub-categories',
                    'show-sub-categories',
                    'create-sub-categories',
                    'edit-sub-categories',
                    'delete-sub-categories',
                    'view-sectors',
                    'show-sectors',
                    'create-sectors',
                    'edit-sectors',
                    'delete-sectors',
                    'view-activities',
                    'show-activities',
                    'create-activities',
                    'edit-activities',
                    'delete-activities',
                    'view-sub-activities',
                    'show-sub-activities',
                    'create-sub-activities',
                    'edit-sub-activities',
                    'delete-sub-activities',
                    'view-entities',
                    'show-entities',
                    'create-entities',
                    'edit-entities',
                    'delete-entities',
                    'view-incubators',
                    'show-incubators',
                    'create-incubators',
                    'edit-incubators',
                    'delete-incubators',
                    'view-form-fields',
                    'show-form-fields',
                    'create-form-fields',
                    'edit-form-fields',
                    'delete-form-fields',
                    'view-stages',
                    'show-stages',
                    'create-stages',
                    'edit-stages',
                    'delete-stages',
                    'view-stage-statuses',
                    'show-stage-statuses',
                    'create-stage-statuses',
                    'edit-stage-statuses',
                    'delete-stage-statuses',
                    'view-applicant-users',
                    'show-applicant-users',
                    'create-applicant-users',
                    'edit-applicant-users',
                    'delete-applicant-users',
                    'view-admin-users',
                    'show-admin-users',
                    'create-admin-users',
                    'edit-admin-users',
                    'delete-admin-users',
                    'view-entity-users',
                    'show-entity-users',
                    'create-entity-users',
                    'edit-entity-users',
                    'delete-entity-users',
                    'view-roles',
                    'show-roles',
                    'create-roles',
                    'edit-roles',
                    'delete-roles',
                    'view-promotional-emails',
                    'delete-promotional-emails',
                    'view-quality-checks',
                    'show-quality-checks',
                    'request-quality-checks',
                    'approve-quality-checks',
                    'reject-quality-checks',
                    'view-audit-talent-applications',
                    'view-audit-entrepreneur-applications',
                    'view-audit-investor-applications',
                    'view-audit-executive-applications',
                    'view-audit-categories',
                    'view-audit-sub-categories',
                    'view-audit-sectors',
                    'view-audit-activities',
                    'view-audit-sub-activities',
                    'view-audit-entities',
                    'view-audit-incubators',
                    'view-audit-form-fields',
                    'view-audit-stages',
                    'view-audit-stage-statuses',
                    'view-audit-applicant-users',
                    'view-audit-admin-users',
                    'view-audit-entity-users',
                    'view-audit-roles',
                    'view-audit-promotional-emails',
                    'view-deleted-audit',
                    'view-talent-application-statistacs',
                    'view-entrepreneur-application-statistacs',
                    'view-investor-application-statistacs',
                    'view-executive-application-statistacs',
                    'view-talent-category-statistacs',
                    'view-entrepreneur-category-statistacs',
                    'view-investor-category-statistacs',
                    'view-executive-category-statistacs',
                    'view-talent-entities-performance-statistacs',
                    'view-entrepreneur-entities-performance-statistacs',
                    'view-investor-entities-performance-statistacs',
                    'view-executive-entities-performance-statistacs',
                    'view-monthly-statistacs',
                ]);
            }
        }
    }
}
