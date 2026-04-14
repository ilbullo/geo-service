<div class="geo-tracker-container" 
     x-data="geoTracker()" 
     x-init="initTracker()">
    
    @if($showMap)
        {{-- Pannello Info: Visibile solo se showMap è true --}}
        <div class="info-panel" style="margin-bottom: 1rem; font-family: sans-serif;">
            @if($lat && $lng)
                <div style="color: green;">
                    <strong>📍 Posizione Attiva:</strong> {{ $lat }}, {{ $lng }}
                    <span style="font-size: 0.8rem; color: #666;">(Aggiornato: {{ now()->format('H:i:s') }})</span>
                </div>
            @else
                <div style="color: #d9534f;">
                    <strong>🛰️ Segnale GPS:</strong> In attesa di segnale...
                </div>
            @endif
        </div>

        {{-- Contenitore Mappa: Visibile solo se showMap è true --}}
        <div id="osm-map" 
             wire:ignore 
             style="height: 400px; width: 100%; border-radius: 8px; border: 1px solid #ccc;">
        </div>

        {{-- Carichiamo Leaflet solo se serve la visualizzazione --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endif

    <script>
        function geoTracker() {
            return {
                map: null,
                marker: null,
                showMap: @js($showMap),
                defaultZoom: @js($zoom),

                initTracker() {
                    // Inizializza la mappa Leaflet SOLO se showMap è attivo
                    if (this.showMap) {
                        this.map = L.map('osm-map').setView([{{ $lat ?? 41.8902 }}, {{ $lng ?? 12.4922 }}], this.defaultZoom);
                        
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap'
                        }).addTo(this.map);

                        @if($lat && $lng)
                            this.updateMap({{ $lat }}, {{ $lng }});
                        @endif
                    }

                    // Avvia sempre il monitoraggio GPS, indipendentemente dalla mappa
                    this.startWatching();
                },

                startWatching() {
                    if (!navigator.geolocation) {
                        console.error("Il browser non supporta la geolocalizzazione.");
                        return;
                    }

                    navigator.geolocation.watchPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            // 1. Aggiorna la mappa solo se visibile
                            if (this.showMap && typeof L !== 'undefined') {
                                this.updateMap(lat, lng);
                            }

                            // 2. Salva SEMPRE sul Database tramite Livewire
                            if (this.$wire) {
                                this.$wire.updateLocation(lat, lng);
                            }
                        },
                        (error) => {
                            console.warn("Errore GPS: " + error.message);
                        },
                        {
                            enableHighAccuracy: true, // Meglio true per il tracciamento invisibile
                            maximumAge: 5000,        
                            timeout: 15000            
                        }
                    );
                },

                updateMap(lat, lng) {
                    // Evitiamo errori se Leaflet non è caricato
                    if (!this.map) return;

                    const newPos = [lat, lng];
                    const customIcon = L.icon({
                        iconUrl: @js($activeIconUrl),
                        shadowUrl: @js($configDefault['shadow']),
                        iconSize: @js($configDefault['size']),
                        iconAnchor: @js($configDefault['anchor']),
                    });

                    if (!this.marker) {
                        this.marker = L.marker(newPos, { icon: customIcon }).bindPopup('<b>Sei qui</b>').addTo(this.map);
                    } else {
                        this.marker.setLatLng(newPos);
                    }
                    
                    this.map.panTo(newPos);
                }
            }
        }
    </script>
</div>