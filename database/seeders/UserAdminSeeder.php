<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Super Admin User
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@yopmail.com',
            'password' => Hash::make('Jusour@2025')
        ]);
        $superAdmin->assignRole('admin');
        $superAdmin->assignLevel('super-admin',2);

        // Creating Admin User
        $admin = User::create([
            'name' => 'Junior Admin',
            'email' => 'junioradmin@yopmail.com',
            'password' => Hash::make('Jusour@2025')
        ]);
        $admin->assignRole('admin');
        $admin->assignLevel('junior-admin',1);

        // Creating Admin User
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@yopmail.com',
            'password' => Hash::make('Jusour@2025')
        ]);
        $admin->assignRole('admin');
        $admin->assignLevel('admin',2);

        // Creating Applicant User
        $applicant = User::create([
            'name' => 'Applicant',
            'email' => 'caspertalks@yopmail.com',
            'password' => Hash::make('Jusour@2025')
        ]);
        $applicant->assignRole('applicant');

        // Creating Entity User
        $entity = User::create([
            'name' => 'Entity Officer',
            'email' => 'entity-officer@yopmail.com',
            'password' => Hash::make('Jusour@2025')
        ]);
        $entity->assignRole('entity');
        $entity->assignLevel('officer',1);

        // Creating Entity User
        $entity = User::create([
            'name' => 'Entity Supervisor',
            'email' => 'entity-supervisor@yopmail.com',
            'password' => Hash::make('Jusour@2025')
        ]);
        $entity->assignRole('entity');
        $entity->assignLevel('supervisor',2);

        // Creating Entity User
        $entity = User::create([
            'name' => 'Entity',
            'email' => 'entity-manager@yopmail.com',
            'password' => Hash::make('Jusour@2025')
        ]);
        $entity->assignRole('entity');
        $entity->assignLevel('manager',3);
    }
}
