<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DatabaseConnectionCountCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Checks\Checks\DatabaseTableSizeCheck;

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
        Health::checks([
            CacheCheck::new(),
            OptimizedAppCheck::new(),            
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
            DatabaseCheck::new(),
            DatabaseConnectionCountCheck::new()
            ->warnWhenMoreConnectionsThan(50)
            ->failWhenMoreConnectionsThan(100),
            UsedDiskSpaceCheck::new()
            ->warnWhenUsedSpaceIsAbovePercentage(60)
            ->failWhenUsedSpaceIsAbovePercentage(80),
            DatabaseTableSizeCheck::new()
            ->table('clientes', maxSizeInMb: 1_000)
            ->table('repconsignaciones', maxSizeInMb: 1_000),
            ]);
    }
}
