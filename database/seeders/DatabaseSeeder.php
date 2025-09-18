<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Artisan::call('cache:clear');
        Model::unguard();

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            NotificationSeeder::class,
            CategoriesSeeder::class,
            SectorsSeeder::class,
            UserAdminSeeder::class,
        ]);

        Model::reguard();
    }
}
