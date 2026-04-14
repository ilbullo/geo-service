<?php

namespace IlBullo\GeoService;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use IlBullo\GeoService\Http\Livewire\GeoTracker;
use IlBullo\GeoService\Http\Livewire\GeoMap;

class GeoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/geo-service.php', 'geoservice');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'geoservice');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/geo-service.php' => config_path('geo-service.php'),
            ], 'geoservice-config');
        }

        $this->registerLivewireComponents();
    }

    protected function registerLivewireComponents(): void
    {
        if (class_exists(Livewire::class)) {
            Livewire::component('geo-tracker', GeoTracker::class);
            Livewire::component('geo-map', GeoMap::class);
        }
    }
}