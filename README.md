## 🇮🇹 Italiano - Guida Rapida

### 1\. Requisiti Frontend

# 

Assicurati di avere Leaflet.js incluso nel tuo file `app.blade.php` (o nel layout dove userai la mappa):

HTML

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

### 2\. Configurazione (`config/geo-service.php`)

# 

Dopo aver pubblicato la config, assicurati di definire le icone:

PHP

    return [
        'refresh_interval' => '10s', // Tempo di polling
        'default_icon' => [
            'url' => 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
            'size' => [32, 32],
            'anchor' => [16, 32],
        ],
        'icons' => [
            \App\Models\User::class => '/images/user-icon.png',
        ],
    ];

### 3\. Utilizzo dei Componenti

#### A. Il Tracker (Per inviare la posizione)

# 

Inserisci questo componente per permettere a un utente o a un mezzo di inviare le coordinate GPS:

HTML

    @livewire('geo-tracker', ['model' => Auth::user()])

_Il componente richiederà i permessi di geolocalizzazione al browser e aggiornerà la tabella `locations`._

#### B. La Mappa (Per monitorare la flotta)

# 

Visualizza tutti i tuoi modelli geolocalizzati in tempo reale:

HTML

    @livewire('geo-map', ['models' => \App\Models\User::all()])

* * *

## 🇺🇸 English - Quick Start

### 1\. Frontend Requirements

# 

You must include Leaflet.js assets in your main layout:

HTML

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

### 2\. The Components

# 

-   **GeoTracker**: Sends GPS data from the client to the server for a specific model.
    
-   **GeoMap**: Displays markers on a map with auto-refresh (polling) based on your config.