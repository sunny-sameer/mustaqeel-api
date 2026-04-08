<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $permissions = Permission::orderBy('id','ASC')->get()->pluck('name')->toArray();
        // Creating Super Admin User
        $superAdmin = User::where(['email' => 'superadmin@yopmail.com'])->first();
        if(empty($superAdmin)){
            $superAdmin = User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@yopmail.com',
                'password' => Hash::make('Jusour@2025')
            ]);
            $superAdmin->assignRole('admin');
            $superAdmin->assignRoleLevel('admin','super-admin');
        }
            // no need for user permissions it is running on behalf of role
        // $superAdmin->givePermissionTo([]);
        // $superAdmin->givePermissionTo($permissions);

        // Creating Admin User
        $junior = User::where(['email' => 'junioradmin@yopmail.com'])->first();
        if(empty($junior)){
            $junior = User::create([
                'name' => 'Junior Admin',
                'email' => 'junioradmin@yopmail.com',
                'password' => Hash::make('Jusour@2025')
            ]);
            $junior->assignRole('admin');
            $junior->assignRoleLevel('admin','junior');
        }
        // $junior->givePermissionTo([]);
        // $junior->givePermissionTo($permissions);

        // Creating Admin User
        $admin = User::where(['email' => 'admin@yopmail.com'])->first();
        if(empty($admin)){
            $admin = User::create([
                'name' => 'admin',
                'email' => 'admin@yopmail.com',
                'password' => Hash::make('Jusour@2025')
            ]);
            $admin->assignRole('admin');
            $admin->assignRoleLevel('admin','manager');
        }
        // $admin->givePermissionTo([]);
        // $admin->givePermissionTo($permissions);

        // Creating Applicant User
        $usr = User::where(['email' => 'caspertalks@yopmail.com'])->first();
        if(empty($usr)){
            $applicant = User::create([
                'name' => 'Applicant',
                'email' => 'caspertalks@yopmail.com',
                'password' => Hash::make('Jusour@2025')
            ]);
            $applicant->assignRole('applicant');
        }
    }
}
