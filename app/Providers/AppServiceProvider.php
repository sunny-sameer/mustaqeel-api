<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //

        $repoArray = [
            "Core" => ["Core"],
            "Admin" => ["Generic"],
            "Users" => ["Users"],
            "Comms" => ["Comms"],
            "Requests" => ["Requests"],
        ];
        foreach ($repoArray as $_dir => $_names) {
            foreach ($_names as $_eachName) {
                $dir = $_dir;

                $this->app->bind(
                    "App\Repositories\V1\\" . $dir . "\\" . $_eachName . "Interface",
                    "App\Repositories\V1\\" . $dir . "\\" . $_eachName . "Repository"
                );
                $this->app->alias("App\Repositories\V1\\" . $dir . "\\" . $_eachName . "Interface", $_eachName);
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
