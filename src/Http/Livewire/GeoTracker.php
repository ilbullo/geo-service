<?php

namespace IlBullo\GeoService\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class GeoTracker extends Component
{
    // Il modello generico (User, Truck, Store, ecc.)
    public $model; 
    
    public $lat;
    public $lng;

    /**
     * Il metodo mount accetta il modello dall'esterno
     */
    public function mount($model = null)
    {
        // Se non viene passato nulla, proviamo l'utente loggato (opzionale)
        $this->model = $model ?? Auth::user();

        // Carichiamo le coordinate esistenti se presenti
        if ($this->model && method_exists($this->model, 'location')) {
            $location = $this->model->location;
            if ($location) {
                $this->lat = $location->lat;
                $this->lng = $location->lng;
            }
        }
    }

    public function updateLocation($lat, $lng)
    {
        // Verifichiamo che il modello abbia effettivamente il Trait/metodo
        if ($this->model && method_exists($this->model, 'updatePosition')) {
            $this->model->updatePosition($lat, $lng, 'browser');
            
            $this->lat = $lat;
            $this->lng = $lng;
        }
    }

    public function render()
    {
        // Recuperiamo l'icona specifica per questo modello o quella di default
        $icons = config('geoservice.icons', []);
        $defaultIcon = config('geoservice.default_icon');
        
        // Cerchiamo se il modello attuale ha un'icona dedicata
        $modelClass = get_class($this->model);
        $activeIconUrl = $icons[$modelClass] ?? $defaultIcon['url'];

        return view('geoservice::livewire.geo-tracker', [
            'activeIconUrl' => $activeIconUrl,
            'configDefault' => $defaultIcon
        ]);
    }
}