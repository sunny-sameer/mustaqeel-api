<?php

namespace Database\Seeders;

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
                'approval_levels'=>2
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
                'approval_levels'=>3
            ],
            [
                'name'=>'incubator',
                'type'=>'entity',
                'approval_levels'=>1
            ],
            [
                'name'=>'moci',
                'type'=>'entity',
                'approval_levels'=>1
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // $admin->givePermissionTo([
        //     'view-role',
        //     'create-role',
        //     'edit-role',
        //     'delete-role',
        //     'view-user',
        //     'create-user',
        //     'edit-user',
        //     'delete-user'
        // ]);
    }
}
