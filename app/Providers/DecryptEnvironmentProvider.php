<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DecryptEnvironmentProvider extends ServiceProvider
{
    public function register()
    {
        // No services to register here
    }

    public function boot()
    {
        if (env('ENCRYPTED_DB_USERNAME')) {
            config(['database.connections.mysql.username' => decrypt(env('ENCRYPTED_DB_USERNAME'))]);
        }

        if (env('ENCRYPTED_DB_PASSWORD')) {
            config(['database.connections.mysql.password' => decrypt(env('ENCRYPTED_DB_PASSWORD'))]);
        }
    }
}
