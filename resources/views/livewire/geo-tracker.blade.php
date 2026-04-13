<div class="geo-tracker-container" 
     x-data="geoTracker()" 
     x-init="initTracker()">
    
    <div class="info-panel" style="margin-bottom: 1rem; font-family: sans-serif;">
        @if($lat && $lng)
            <div style="color: green;">
                <strong>📍 Posizione Attiva:</strong> {{ $lat }}, {{ $lng }}
                <span style="font-size: 0.8rem; color: #666;">(Aggiornato: {{ now()->format('H:i:s') }})</span>
            </div>
        @else
            <div style="color: #d9534f;">
                <strong>🛰️ Segnale GPS:</strong> In attesa di autorizzazione o segnale...
            </div>
        @endif
    </div>

    <div id="osm-map" 
         wire:ignore 
         style="height: 400px; width: 100%; border-radius: 8px; border: 1px solid #ccc;">
    </div>

    {{-- Caricamento Asset Leaflet (OpenStreetMap) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        function geoTracker() {
            return {
                map: null,
                marker: null,

                initTracker() {
                    // Inizializza la mappa (Default su Roma se non ci sono coordinate)
                    this.map = L.map('osm-map').setView([{{ $lat ?? 41.8902 }}, {{ $lng ?? 12.4922 }}], 13);
                    
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap'
                    }).addTo(this.map);

                    // Se abbiamo già dati dal server, piazziamo il marker subito
                    @if($lat && $lng)
                        this.marker = L.marker([{{ $lat }}, {{ $lng }}]).addTo(this.map);
                        this.map.setView([{{ $lat }}, {{ $lng }}], 15);
                    @endif

                    this.startWatching();
                },

                startWatching() {
                    // Controllo se il browser supporta la geolocalizzazione
                    if (!navigator.geolocation) {
                        console.error("Il browser non supporta la geolocalizzazione.");
                        return;
                    }

                    // Avviamo il monitoraggio continuo
                    navigator.geolocation.watchPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            // 1. Aggiorna immediatamente il pin sulla mappa (Feedback visivo)
                            this.updateMap(lat, lng);

                            // 2. Invia i dati al componente Livewire per il salvataggio su DB
                            // this.$wire è la variabile magica di Alpine per parlare con Livewire 3
                            if (this.$wire) {
                                this.$wire.updateLocation(lat, lng);
                            }
                        },
                        (error) => {
                            // Gestione errori silenziosa in console per non disturbare l'utente
                            const errors = {
                                1: "Permesso negato dall'utente.",
                                2: "Posizione non disponibile (Hardware/Segnale).",
                                3: "Timeout scaduto durante la ricerca."
                            };
                            console.warn("Messaggio GPS: " + (errors[error.code] || error.message));
                        },
                        {
                            // IMPOSTAZIONI OTTIMIZZATE
                            enableHighAccuracy: false, // Evita l'errore (2) al chiuso usando Wi-Fi/Celle
                            maximumAge: 10000,        // Accetta posizioni in cache vecchie di max 10 secondi
                            timeout: 20000            // Aspetta fino a 20 secondi per il primo fix
                        }
                    );
                },

                updateMap(lat, lng) {
                    const newPos = [lat, lng];
                    
                    // Prepariamo l'icona personalizzata dal config
                    const customIcon = L.icon({
                        iconUrl: @js($activeIconUrl),
                        shadowUrl: @js($configDefault['shadow']),
                        iconSize: @js($configDefault['size']),
                        iconAnchor: @js($configDefault['anchor']),
                        popupAnchor: [1, -34]
                    });

                    if (!this.marker) {
                        // Creazione del primo marker con l'icona personalizzata
                        this.marker = L.marker(newPos, { icon: customIcon }).addTo(this.map);
                    } else {
                        // Aggiornamento posizione
                        this.marker.setLatLng(newPos);
                    }

                    const modelName = @js(class_basename($model));
                    this.marker.bindPopup(`<b>${modelName}</b>`);
                    
                    this.map.panTo(newPos);
                }
            }
        }
    </script>
</div>