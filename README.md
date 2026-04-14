  GeoService Documentation  body { box-sizing: border-box; min-width: 200px; max-width: 980px; margin: 0 auto; padding: 45px; } @media (max-width: 767px) { .markdown-body { padding: 15px; } } .badge { display: flex; gap: 10px; margin-bottom: 20px; }

GeoService Package
==================

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel) ![Livewire](https://img.shields.io/badge/Livewire-3.x-FB70A9?style=for-the-badge&logo=livewire)

**GeoService** è un package Laravel progettato per gestire la geolocalizzazione di modelli polimorfici in modo elegante, seguendo i principi SOLID e i design pattern moderni. È ideale per gestionali di flotte, tracciamento utenti o monitoraggio di asset in tempo reale.

* * *

🇮🇹 Italiano
-------------

### Caratteristiche

*   **Polimorfismo Totale**: Collega la posizione GPS a qualsiasi modello (Utenti, Veicoli, Negozi, ecc.).
*   **Popup Indipendenti dal Modello**: Ogni modello decide autonomamente cosa mostrare nel popup della mappa tramite un'interfaccia dedicata.
*   **Refresh Automatico**: Monitoraggio in tempo reale con intervallo di aggiornamento configurabile.
*   **Architettura Solida**: Utilizzo di Service, DTO e Interfacce per la massima manutenibilità.
*   **Frontend Reattivo**: Integrazione fluida tra Livewire, Alpine.js e Leaflet.

### Installazione Passo Passo

1.  Aggiungi il package (se locale, definisci il path nel `composer.json` del progetto principale):
    
        "repositories": [
            {
                "type": "path",
                "url": "packages/ilbullo/geo-service"
            }
        ]
    
    Poi esegui: `composer require ilbullo/geo-service`
2.  Pubblica la configurazione: `php artisan vendor:publish --tag=geoservice-config`
3.  Esegui le migrazioni: `php artisan migrate`

* * *

🇺🇸 English
------------

### Features

*   **Total Polymorphism**: Link GPS positions to any model (Users, Vehicles, Stores, etc.).
*   **Model-Independent Popups**: Each model autonomously decides what to display in the map popup via a dedicated interface.
*   **Automatic Refresh**: Real-time monitoring with a configurable update interval.
*   **Solid Architecture**: Uses Services, DTOs, and Interfaces for maximum maintainability.
*   **Reactive Frontend**: Seamless integration between Livewire, Alpine.js, and Leaflet.

### Usage Example

    // Implement the interface in your Model
    class Vehicle extends Model implements GeolocatablePopup {
        use HasGeolocation, HasGeolocatablePopup;
    
        public function toMapPopup(): string {
            return "<b>Vehicle ID: {$this->id}</b>";
        }
    }
    
    // In your Blade view
    @livewire('geo-map', ['models' => $fleet])