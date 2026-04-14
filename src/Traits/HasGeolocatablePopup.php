<?php

namespace IlBullo\GeoService\Traits;

trait HasGeolocatablePopup
{
    /**
     * Fallback predefinito per l'icona.
     * Se il modello ha bisogno di un'icona specifica, 
     * può semplicemente sovrascrivere questo metodo.
     */
    public function getMapIcon(): ?string
    {
        return null;
    }
}