## 🇮🇹 Italiano - Guida Rapida

### 

Benvenuto nel manuale tecnico ufficiale di **GeoService**. Questa guida ti spiegherà ogni singolo ingranaggio del package, dalla cattura del segnale GPS alla visualizzazione dinamica sulla mappa.

Il package si basa su tre pilastri fondamentali: **Cattura** (Input), **Trasformazione** (Logica) e **Visualizzazione** (Output).

* * *

## 🛠 1. Preparazione dell'Infrastruttura

### 

Prima di muovere i primi passi, il tuo ambiente Laravel deve essere pronto ad accogliere i dati geografici.

### Requisiti Frontend

### 

Il package utilizza **Leaflet.js** per il rendering cartografico e **Alpine.js** per la gestione degli eventi. Nel tuo layout principale (`app.blade.php`), inserisci:

HTML

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

### Configurazione Globale (`config/geo-service.php`)

### 

Il cuore visivo si gestisce qui. Puoi definire icone diverse per ogni classe di modello o un'icona di fallback:

-   **`refresh_interval`**: Definisce ogni quanto la mappa deve interrogare il server per aggiornare le posizioni (es: '10s').
    
-   **`default_icon`**: Array contenente URL, dimensioni e punto di ancoraggio dell'icona standard.
    
-   **`icons`**: Un mapping `Classe => URL` per assegnare automaticamente icone a modelli diversi (es: `Vehicle::class => '/truck.png'`).
    

* * *

## 📡 2. GeoTracker: Il sensore di movimento

### 

Il componente `GeoTracker` è l'interfaccia tra il dispositivo fisico e il database.

### Come funziona

### 

1.  **Rilevamento**: All'avvio, il componente richiede i permessi GPS al browser.
    
2.  **Sincronizzazione**: Utilizza il metodo `updatePosition()` per salvare le coordinate nella tabella polimorfica `locations`.
    
3.  **Persistenza**: Salva latitudine, longitudine, il provider (es: 'browser') e l'ultimo timestamp utile (`last_seen_at`).
    

### Utilizzo nel codice

### 

Puoi inizializzare il tracker per l'utente autenticato o per un'entità specifica:

HTML

    @livewire('geo-tracker')
    
    @livewire('geo-tracker', ['model' => $veicolo])

* * *

## 🧠 3. La Logica: MapService e DTO

### 

Questa è la parte "invisibile" che garantisce la pulizia del codice (SOLID).

-   **`MapMarkerDTO`**: Trasforma i dati grezzi del database in un oggetto standardizzato e serializzabile in JSON per il frontend, garantendo che Leaflet riceva sempre dati validi.
    
-   **`MapService`**: Si occupa di filtrare i modelli e generare i marker. Recupera le `Location` associate ai modelli passati, estrae l'HTML del popup e l'URL dell'icona corretta.
    

### L'Interfaccia `GeolocatablePopup`

### 

Per rendere un modello "mappabile", deve implementare questo contratto:

1.  **`toMapPopup()`**: Ritorna la stringa HTML che apparirà cliccando sul marker.
    
2.  **`getMapIcon()`**: Permette a una singola istanza (es: un mezzo in emergenza) di cambiare icona dinamicamente, ignorando le impostazioni globali.
    

* * *

## 🗺 4. GeoMap: La Centrale Operativa

### 

Il componente `GeoMap` visualizza i dati e gestisce il refresh automatico (polling).

### Passare modelli personalizzati alla mappa

### 

Questa è la funzione più potente: puoi decidere **esattamente cosa mostrare** sulla mappa passando un array o una collezione di modelli alla proprietà `$models`.

| **Scenario** | **Esempio di Codice** |
| --- | --- |
| **Intera Flotta** | `@livewire('geo-map', ['models' => Vehicle::all()])` |
| **Solo Mezzi Attivi** | `@livewire('geo-map', ['models' => Vehicle::where('status', 'moving')->get()])` |
| **Singolo Utente** | `@livewire('geo-map', ['models' => [$user]])` |
| **Mix Polimorfico** | `@livewire('geo-map', ['models' => $fleet->merge($pointsOfInterest)])` |

### Ciclo di vita del refresh

### 

1.  Il componente si avvia e carica i marker iniziali tramite `loadMap()`.
    
2.  Ogni `X` secondi (configurati nel file `geo-service.php`), il componente riesegue la query.
    
3.  L'evento `map-updated` viene inviato al frontend.
    
4.  **Alpine.js** riceve i nuovi DTO, pulisce i vecchi marker e posiziona quelli nuovi senza ricaricare la pagina.
    

* * *

## 💎 5. Riassunto dei Trait

### 

Per rendere operativo un tuo modello Eloquent (es. `App\Models\Truck`), ti basta aggiungere queste righe:

PHP

    use IlBullo\GeoService\Traits\HasGeolocation;
    use IlBullo\GeoService\Traits\HasGeolocatablePopup;
    use IlBullo\GeoService\Contracts\GeolocatablePopup;
    
    class Truck extends Model implements GeolocatablePopup 
    {
        use HasGeolocation;      // Gestisce la relazione con la tabella locations
        use HasGeolocatablePopup; // Fornisce implementazioni base per icone e popup
    
        public function toMapPopup(): string 
        {
            return "<b>Camion:</b> {$this->license_plate}";
        }
    }

Ora il tuo sistema di gestione flotta è pronto. Puoi tracciare mezzi, visualizzarli filtrati per categoria e vederli muoversi in tempo reale con zero configurazione aggiuntiva sul frontend.

## 🇺🇸 English - Quick Start

### 


Welcome to the official **GeoService** technical manual. This guide explains every gear within the package, from capturing the GPS signal to dynamic map visualization.

The package is built on three fundamental pillars: **Capture** (Input), **Transformation** (Logic), and **Visualization** (Output).

* * *

## 🛠 1. Infrastructure Preparation

## 

Before taking your first steps, your Laravel environment must be ready to receive geographic data.

### Frontend Requirements

## 

The package uses **Leaflet.js** for cartographic rendering and **Alpine.js** for event management. In your main layout (`app.blade.php`), include the following:

HTML

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

### Global Configuration (`config/geo-service.php`)

## 

The visual core is managed here. You can define different icons for each model class or a fallback icon:

-   **`refresh_interval`**: Defines how often the map should poll the server to update positions (e.g., '10s').
    
-   **`default_icon`**: An array containing the URL, dimensions, and anchor point of the standard icon.
    
-   **`icons`**: A `Class => URL` mapping to automatically assign icons to different models (e.g., `Vehicle::class => '/truck.png'`).
    

* * *

## 📡 2. GeoTracker: The Motion Sensor

## 

The `GeoTracker` component serves as the interface between the physical device and the database.

### How it Works

## 

1.  **Detection**: Upon startup, the component requests GPS permissions from the browser.
    
2.  **Synchronization**: It utilizes the `updatePosition()` method to save coordinates into the polymorphic `locations` table.
    
3.  **Persistence**: It saves latitude, longitude, the provider (e.g., 'browser'), and the last known timestamp (`last_seen_at`).
    

### Code Usage

## 

You can initialize the tracker for the authenticated user or for a specific entity:

HTML

    @livewire('geo-tracker')
    
    @livewire('geo-tracker', ['model' => $vehicle])

* * *

## 🧠 3. The Logic: MapService and DTO

## 

This is the "invisible" part that ensures clean code (SOLID).

-   **`MapMarkerDTO`**: Transforms raw database data into a standardized, JSON-serializable object for the frontend, ensuring Leaflet always receives valid data.
    
-   **`MapService`**: Handles model filtering and marker generation. It retrieves the `Location` records associated with the passed models, extracting the popup HTML and the correct icon URL.
    

### The `GeolocatablePopup` Interface

## 

To make a model "mappable," it must implement this contract:

1.  **`toMapPopup()`**: Returns the HTML string that will appear when clicking the marker.
    
2.  **`getMapIcon()`**: Allows a single instance (e.g., a vehicle in an emergency state) to dynamically change its icon, ignoring global settings.
    

* * *

## 🗺 4. GeoMap: The Command Center

## 

The `GeoMap` component visualizes the data and manages automatic refresh (polling).

### Passing Custom Models to the Map

## 

This is the most powerful feature: you can decide **exactly what to show** on the map by passing an array or a collection of models to the `$models` property.

| **Scenario** | **Code Example** |
| --- | --- |
| **Entire Fleet** | `@livewire('geo-map', ['models' => Vehicle::all()])` |
| **Only Active Vehicles** | `@livewire('geo-map', ['models' => Vehicle::where('status', 'moving')->get()])` |
| **Single User** | `@livewire('geo-map', ['models' => [$user]])` |
| **Polymorphic Mix** | `@livewire('geo-map', ['models' => $fleet->merge($pointsOfInterest)])` |

### Refresh Lifecycle

## 

1.  The component starts and loads initial markers via `loadMap()`.
    
2.  Every `X` seconds (configured in `geo-service.php`), the component re-executes the query.
    
3.  The `map-updated` event is dispatched to the frontend.
    
4.  **Alpine.js** receives the new DTOs, clears old markers, and places new ones without a page reload.
    

* * *

## 💎 5. Trait Summary

## 

To make your Eloquent model (e.g., `App\Models\Truck`) operational, simply add these lines:

PHP

    use IlBullo\GeoService\Traits\HasGeolocation;
    use IlBullo\GeoService\Traits\HasGeolocatablePopup;
    use IlBullo\GeoService\Contracts\GeolocatablePopup;
    
    class Truck extends Model implements GeolocatablePopup 
    {
        use HasGeolocation;      // Manages the relationship with the locations table
        use HasGeolocatablePopup; // Provides base implementations for icons and popups
    
        public function toMapPopup(): string 
        {
            return "<b>Truck:</b> {$this->license_plate}";
        }
    }

Your fleet management system is now ready. You can track assets, visualize them filtered by category, and see them move in real-time with zero additional frontend configuration.