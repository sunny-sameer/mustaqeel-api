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
        $superAdmin->assignRole('super-admin');

        // Creating Admin User
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@yopmail.com',
            'password' => Hash::make('Jusour@2025')
        ]);
        $admin->assignRole('admin');

        // Creating Admin User
        $admin = User::create([
            'name' => 'Applicant',
            'email' => 'caspertalks@yopmail.com',
            'password' => Hash::make('Jusour@2025')
        ]);
        $admin->assignRole('applicant');
    }
}
