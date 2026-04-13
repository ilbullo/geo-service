<?php

namespace IlBullo\GeoService;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use IlBullo\GeoService\Http\Livewire\GeoTracker;

class GeoServiceProvider extends ServiceProvider
{
    /**
     * Registra i servizi nel container.
     * Viene eseguito PRIMA di tutto il resto.
     */
    public function register()
    {
        // Uniamo la configurazione del package (se ne avremo una)
        $this->mergeConfigFrom(__DIR__.'/../config/geo-service.php', 'geoservice');
    }

    /**
     * Avvia i servizi del package.
     * Qui carichiamo rotte, migrazioni, viste e componenti.
     */
    public function boot()
    {
        // 1. Carichiamo le migrazioni (fondamentale per creare la tabella locations)
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // 2. Carichiamo le viste Blade (per i componenti della mappa)
        // Potrai usarle con il prefisso 'geoservice::nome-vista'
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'geoservice');

        // 3. Registriamo il componente Livewire (quando lo scriveremo tra poco)
        // Questo permetterà di usare @livewire('geo-tracker') ovunque
        if (class_exists(Livewire::class)) {

            // Registra il tracker (che aggiorna la posizione)
             Livewire::component('geo-tracker', GeoTracker::class);

             // Registra la mappa (che visualizza i dati)
            \Livewire\Livewire::component('geo-map', \IlBullo\GeoService\Http\Livewire\GeoMap::class);
        }
    }
}