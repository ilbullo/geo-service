<?php

namespace IlBullo\GeoService\Contracts;

interface GeolocatablePopup
{
    /**
     * Ritorna l'HTML da mostrare nel popup della mappa.
     */
    public function toMapPopup(): string;

    /**
     * Ritorna l'URL dell'icona specifica. Se null, usa il default.
     */
    public function getMapIcon(): ?string;
}