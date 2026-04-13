<?php 

namespace IlBullo\GeoService\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['lat', 'lng', 'provider', 'last_seen_at'];

    public function geolocatable()
    {
        return $this->morphTo();
    }
}