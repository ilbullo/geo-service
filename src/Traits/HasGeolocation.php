<?php 

namespace IlBullo\GeoService\Traits;

use IlBullo\GeoService\Models\Location;

trait HasGeolocation
{
    public function location()
    {
        return $this->morphOne(Location::class, 'geolocatable');
    }

    public function updatePosition($lat, $lng, $provider = 'browser')
    {
        return $this->location()->updateOrCreate(
            [], // Cerchiamo per relazione polimorfica
            [
                'lat' => $lat,
                'lng' => $lng,
                'provider' => $provider,
                'last_seen_at' => now(),
            ]
        );
    }
}