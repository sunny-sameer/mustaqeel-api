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
            'super-admin',
            'admin',
            'applicant',
            'entity-manager',
            'entity-supervisor',
            'entity-officer',
            'incubator',
            'pilot',
            'investor',
            'moci',
            'bank'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
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
