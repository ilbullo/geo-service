<?php

namespace IlBullo\GeoService\Services;

use IlBullo\GeoService\Models\Location;
use IlBullo\GeoService\DTOs\MapMarkerDTO;
use IlBullo\GeoService\Contracts\GeolocatablePopup;

class MapService
{
    public function getMarkersForModels(iterable $models = []): array
    {
        $models = collect($models);

        $query = Location::with('geolocatable');

        if ($models->isNotEmpty()) {
            $query->where(function($q) use ($models) {
                foreach ($models as $model) {
                    $q->orWhere(function($sub) use ($model) {
                        $sub->where('geolocatable_type', get_class($model))
                            ->where('geolocatable_id', $model->id);
                    });
                }
            });
        }

        $iconsConfig = config('geo-service.icons', []);
        $defaultIconUrl = config('geo-service.default_icon.url');

        return $query->get()->map(function($loc) use ($iconsConfig, $defaultIconUrl) {
            $model = $loc->geolocatable;

            $popupHtml = ($model instanceof GeolocatablePopup) 
                ? $model->toMapPopup() 
                : "<b>ID: {$loc->geolocatable_id}</b>";

            // Priorità icona: 1. Metodo modello, 2. Config per tipo, 3. Default
            $iconUrl = ($model instanceof GeolocatablePopup && $model->getMapIcon())
                ? $model->getMapIcon()
                : ($iconsConfig[$loc->geolocatable_type] ?? $defaultIconUrl);

            return new MapMarkerDTO(
                lat: (float)$loc->lat,
                lng: (float)$loc->lng,
                popupHtml: $popupHtml,
                iconUrl: $iconUrl
            );
        })->toArray();
    }
}