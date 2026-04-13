<?php

namespace IlBullo\GeoService\Http\Livewire;

use Livewire\Component;
use IlBullo\GeoService\Models\Location;

class GeoMap extends Component
{
    public $readyToLoad = false;
    
    // Questa proprietà conterrà i modelli passati dall'esterno
    public $models = [];

    public function loadMap()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        $locations = collect();

        if ($this->readyToLoad) {
            $query = Location::with('geolocatable');

            // Se sono stati passati dei modelli, filtriamo la mappa
            if (!empty($this->models)) {
                $query->where(function($q) {
                    foreach ($this->models as $model) {
                        $q->orWhere(function($sub) use ($model) {
                            $sub->where('geolocatable_type', get_class($model))
                                ->where('geolocatable_id', $model->id);
                        });
                    }
                });
            } else {
                // Se non passiamo nulla, mostriamo tutto come prima
                $locations = $query->get();
            }
            
            $locations = $query->get();
        }

        return view('geoservice::livewire.geo-map', [
            'locations'   => $locations,
            'icons'       => config('geoservice.icons', []),
            'defaultIcon' => config('geoservice.default_icon')
        ]);
    }
}