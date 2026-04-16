<?php

namespace Database\Seeders;

use App\Models\RoleLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
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
                'approval_levels'=>3,
                'level' => [
                    [
                        'name' => 'super-admin',
                        'level' => 3
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
            $permissions = Permission::orderBy('id','ASC')->get()->pluck('name')->toArray();
            if($roleData->name == 'admin'){
                $roleData->givePermissionTo([]);
                $roleData->givePermissionTo($permissions);
            }
        }
    }
}
