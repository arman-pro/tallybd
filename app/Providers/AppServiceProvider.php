<?php

namespace App\Providers;

use App\Companydetail;
// use App\ViewComposers\ActiveFinancialYear;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Blade;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
       require_once app_path().'/Helpers/Functions.php';
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        setlocale(LC_MONETARY, 'en_IN');
        date_default_timezone_set('Asia/Dhaka');

    }
}
