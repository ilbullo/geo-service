<div wire:init="loadMap"
     wire:poll.{{ $refreshInterval }}="loadMap"
     x-data="geoMapComponent()"
     @map-updated.window="updateMarkers($event.detail[0])">
     
    <div wire:ignore 
         id="global-map" 
         x-ref="mapContainer"
         style="height: 600px; width: 100%; border-radius: 8px; border: 1px solid #ddd;">
    </div>

    <script>
        function geoMapComponent() {
            return {
                map: null,
                markerGroup: null,
                // Usiamo i valori passati dal componente Livewire
                zoom: @js($zoom),
                center: @js($center),

                init() {
                    // Inizializziamo la mappa usando le coordinate della config
                    this.map = L.map(this.$refs.mapContainer).setView(
                        [this.center.lat, this.center.lng], 
                        this.zoom
                    );

                    L.tileLayer(@js(config('geo-service.map.tile_layer', 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'))).addTo(this.map);
                    this.markerGroup = L.layerGroup().addTo(this.map);

                    // Risolve problemi di rendering se la mappa è in un tab o div nascosto all'inizio
                    setTimeout(() => { this.map.invalidateSize(); }, 500);
                },

                updateMarkers(data) {
                    if (!this.map) return;
                    
                    this.markerGroup.clearLayers();
                    let bounds = [];

                    data.locations.forEach(loc => {
                        let icon = L.icon({
                            iconUrl: loc.icon_url,
                            iconSize: data.config.size,
                            iconAnchor: data.config.anchor,
                            popupAnchor: data.config.popupAnchor || [0, -32]
                        });

                        let marker = L.marker([loc.lat, loc.lng], { icon: icon })
                                      .bindPopup(loc.popup_html);
                        
                        this.markerGroup.addLayer(marker);
                        bounds.push([loc.lat, loc.lng]);
                    });

                    // Gestione intelligente della visuale
                    if (bounds.length > 1) {
                        // Più utenti: inquadrali tutti
                        this.map.fitBounds(bounds, { padding: [50, 50], maxZoom: this.zoom });
                    } else if (bounds.length === 1) {
                        // Un solo utente: centra su di lui con lo zoom di default
                        this.map.setView(bounds[0], this.zoom);
                    } else {
                        // Nessun utente: torna al centro predefinito dalla config
                        this.map.setView([this.center.lat, this.center.lng], this.zoom);
                    }
                }
            }
        }
    </script>
</div>