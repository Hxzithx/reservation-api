<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Business;
use App\Models\Service;
use App\Models\Resource;
use App\Models\Booking;
use App\Models\SystemSetting;
use App\Policies\BusinessPolicy;
use App\Policies\ServicePolicy;
use App\Policies\ResourcePolicy;
use App\Policies\BookingPolicy;
use App\Policies\SystemSettingPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
