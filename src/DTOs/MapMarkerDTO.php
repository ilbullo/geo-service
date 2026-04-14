<?php

namespace IlBullo\GeoService\DTOs;

use JsonSerializable;
use InvalidArgumentException;

class MapMarkerDTO implements JsonSerializable
{
    public function __construct(
        public float $lat,
        public float $lng,
        public string $popupHtml,
        public ?string $iconUrl = null
    ) {
        $this->validateCoordinates($lat, $lng);
    }

    private function validateCoordinates(float $lat, float $lng): void
    {
        if ($lat < -90 || $lat > 90) {
            throw new InvalidArgumentException("Latitudine non valida: $lat. Deve essere tra -90 e 90.");
        }
        if ($lng < -180 || $lng > 180) {
            throw new InvalidArgumentException("Longitudine non valida: $lng. Deve essere tra -180 e 180.");
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'lat' => round($this->lat, 7), // Precisione standard GPS (circa 1cm)
            'lng' => round($this->lng, 7),
            'popup_html' => $this->popupHtml,
            'icon_url' => $this->iconUrl,
        ];
    }
}