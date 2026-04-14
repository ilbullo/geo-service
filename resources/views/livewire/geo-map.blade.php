<div wire:init="loadMap"
     wire:poll.{{ $refreshInterval }}="loadMap"
     x-data="geoMapComponent()"
     @map-updated.window="updateMarkers($event.detail[0])">
     
    <div wire:ignore 
         id="global-map" 
         x-ref="mapContainer"
         style="height: 600px; width: 100%; border-radius: 8px;">
    </div>

    <script>
        function geoMapComponent() {
            return {
                map: null,
                markerGroup: null,

                init() {
                    this.map = L.map(this.$refs.mapContainer).setView([41.9, 12.5], 6);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
                    this.markerGroup = L.layerGroup().addTo(this.map);
                },

                updateMarkers(data) {
                    this.markerGroup.clearLayers();
                    let bounds = [];

                    data.locations.forEach(loc => {
                        let icon = L.icon({
                            iconUrl: loc.icon_url,
                            iconSize: data.config.size,
                            iconAnchor: data.config.anchor,
                            popupAnchor: data.config.popupAnchor || [0, -34]
                        });

                        let marker = L.marker([loc.lat, loc.lng], { icon: icon })
                                      .bindPopup(loc.popup_html);
                        
                        this.markerGroup.addLayer(marker);
                        bounds.push([loc.lat, loc.lng]);
                    });

                    //if (bounds.length > 0) {
                    //    this.map.fitBounds(bounds, { padding: [50, 50], maxZoom: 15 });
                    //}
                    if (bounds.length > 0) {
                        this.map.fitBounds(bounds, { padding: [50, 50], maxZoom: 15 });
                    } else {
                        // Se non ci sono punti, torna alla vista predefinita (Italia/Venezia)
                        this.map.setView([41.9, 12.5], 6);
                    }
                    setTimeout(() => { this.map.invalidateSize(); }, 250);

                    
                }
            }
        }
    </script>
</div>