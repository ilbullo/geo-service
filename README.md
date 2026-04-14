# GeoService Package

**GeoService** è un package Laravel per la geolocalizzazione polimorfica avanzata. Permette di tracciare e visualizzare su mappa qualsiasi modello Eloquent (Utenti, Veicoli, Asset) con icone personalizzate e aggiornamenti in tempo reale.

* * *

## 🇮🇹 Italiano

### 🚀 Caratteristiche

-   **Polimorfismo Nativo**: Collega posizioni GPS a qualsiasi entità del database.
    
-   **Icone Marker Dinamiche**: Configura icone diverse per ogni tipo di modello o per singole istanze.
    
-   **Popup Personalizzabili**: Ogni modello definisce il proprio HTML per i popup della mappa.
    
-   **Real-time Monitoring**: Supporto al polling automatico gestito da configurazione.
    
-   **Architettura SOLID**: Utilizzo di DTO, Service e Contract per una manutenibilità superiore.
    

### 🛠 Installazione

1.  **Aggiungi il repository** nel `composer.json` del tuo progetto:
    
    JSON
    
        "repositories": [{ "type": "path", "url": "packages/ilbullo/geo-service" }]
    
2.  **Installa**: `composer require ilbullo/geo-service`
    
3.  **Pubblica i file**: `php artisan vendor:publish --tag=geoservice-config`
    
4.  **Migra il DB**: `php artisan migrate`
    

### ⚙️ Configurazione Icone e Marker

Dopo la pubblicazione, troverai il file `config/geo-service.php`. Qui puoi gestire il comportamento visivo del package:

PHP

    return [
        // Intervallo di aggiornamento automatico della mappa (es: '5s', '10s', '1m')
        'refresh_interval' => '10s',
    
        // Icona predefinita se non specificata
        'default_icon' => [
            'url' => '/images/markers/default.png',
            'size' => [32, 32],
            'anchor' => [16, 32],
        ],
    
        // Mapping icone per tipo di modello
        'icons' => [
            \App\Models\User::class => '/images/markers/user.png',
            \App\Models\Vehicle::class => '/images/markers/truck.png',
        ],
    ];

### 📖 Utilizzo

#### 1\. Implementazione nel Modello

Usa i Trait e implementa l'interfaccia `GeolocatablePopup`:

PHP

    use IlBullo\GeoService\Traits\HasGeolocation;
    use IlBullo\GeoService\Traits\HasGeolocatablePopup;
    use IlBullo\GeoService\Contracts\GeolocatablePopup;
    
    class Vehicle extends Model implements GeolocatablePopup {
        use HasGeolocation, HasGeolocatablePopup;
    
        public function toMapPopup(): string {
            return "<b>Mezzo:</b> {$this->plate_number}";
        }
    
        // Opzionale: Sovrascrivi l'icona solo per questa specifica istanza
        public function getMapIcon(): ?string {
            return $this->is_active ? '/icons/moving.png' : '/icons/stopped.png';
        }
    }

#### 2\. Visualizzazione Mappa

Inserisci il componente Livewire passando la collezione di modelli da mostrare:

HTML

    @livewire('geo-map', ['models' => $vehicles])

* * *

## 🇺🇸 English

### 🚀 Features

-   **Native Polymorphism**: Link GPS positions to any database entity.
    
-   **Dynamic Marker Icons**: Configure different icons per model type or per specific instance.
    
-   **Customizable Popups**: Each model defines its own HTML for map popups.
    
-   **Real-time Monitoring**: Automatic polling support managed via configuration.
    
-   **SOLID Architecture**: Uses DTOs, Services, and Contracts for top-tier maintainability.
    

### ⚙️ Icon & Marker Configuration

In `config/geo-service.php`, you can define how markers appear:

-   **`refresh_interval`**: How often the map updates automatically.
    
-   **`icons`**: A mapping array where keys are Model classes and values are icon URLs.
    
-   **`getMapIcon()`**: A method in your Model that can override global settings for granular control.
    

### 📖 Quick Start

1.  Add `HasGeolocation` and `HasGeolocatablePopup` traits to your Model.
    
2.  Implement `toMapPopup()` to define the marker content.
    
3.  Place `@livewire('geo-map', ['models' => $items])` in your blade view.