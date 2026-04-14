<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Marker Icon
    |--------------------------------------------------------------------------
    | Qui puoi definire l'URL dell'icona predefinita e le sue dimensioni.
    */
    'default_icon' => [
        'url'    => 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
        'shadow' => 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        'size'   => [25, 41],   // Larghezza, Altezza
        'anchor' => [12, 41],   // Punto dell'icona che tocca la mappa
    ],

    /*
    |--------------------------------------------------------------------------
    | Model Specific Icons
    |--------------------------------------------------------------------------
    | Puoi definire icone diverse per modelli specifici (es. User, Truck, ecc.)
    */
    'icons' => [
        'App\Models\User' => 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        'App\Models\Vehicle' => 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
    ],

    'refresh_interval' => '10s', // Esempio: aggiorna ogni 10 secondi
    'tracker' => [
        'default_zoom' => 15,
        'min_zoom' => 2,
        'max_zoom' => 20,
        // Altre impostazioni...
    ],
    'map' => [
        'default_zoom' => 17, // Zoom più largo per vedere più utenti contemporaneamente
        'center' => [
            'lat' => 45.4338, // Venice come fallback
            'lng' => 12.3286,
        ],
        'tile_layer' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    ]
];