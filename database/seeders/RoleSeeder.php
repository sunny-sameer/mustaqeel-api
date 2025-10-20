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
                'name'=>'super-admin',
                'type'=>'jusour',
            ],
            [
                'name'=>'admin',
                'type'=>'jusour',
            ],
            [
                'name'=>'applicant',
                'type'=>'applicant',
            ],
            [
                'name'=>'investor',
                'type'=>'applicant',
            ],
            [
                'name'=>'entity-manager',
                'type'=>'entity',
            ],
            [
                'name'=>'entity-supervisor',
                'type'=>'entity',
            ],
            [
                'name'=>'entity-officer',
                'type'=>'entity',
            ],
            [
                'name'=>'incubator',
                'type'=>'entity',
            ],
            [
                'name'=>'moci',
                'type'=>'entity',
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
