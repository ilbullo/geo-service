<?php

namespace IlBullo\GeoService\Http\Livewire;

use Livewire\Component;
use IlBullo\GeoService\Services\MapService;

class GeoMap extends Component
{
    public $readyToLoad = false;
    public $models = [];
    public $refreshInterval; 

    public $zoom;
    public $center;

    public function mount($models = [])
    {
        $this->models = $models;
        // Recupera il valore dal config, con un fallback di sicurezza
        $this->refreshInterval = config('geoservice.refresh_interval', '30s');

        $this->zoom = config('geoservice.map.default_zoom', 14);
        $this->center = config('geoservice.map.center');
    }

    /**
     * Usiamo il Service iniettato
     */
    public function loadMap(MapService $mapService)
    {
        $this->readyToLoad = true;
            
        $markers = $mapService->getMarkersForModels($this->models);

        $this->dispatch('map-updated', [
            'locations' => $markers,
            'config'    => config('geoservice.default_icon')
        ]);
    }

    public function render()
    {
        return view('geoservice::livewire.geo-map');
    }
}