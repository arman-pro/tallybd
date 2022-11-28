<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GoogleDriveAdapter extends \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter
{
    public function getService()
    {
            return $this->service;
    }
    
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
