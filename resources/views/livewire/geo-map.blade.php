<div wire:init="loadMap" 
     x-data="{
        map: null,
        markerGroup: null,

        initMap() {
            if (this.map) return;
            
            // Inizializza la mappa
            this.map = L.map('global-map').setView([41.9, 12.5], 6);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.map);

            // Creiamo un gruppo per i marker così possiamo pulirli facilmente
            this.markerGroup = L.layerGroup().addTo(this.map);
        },

        renderMarkers(locations) {
            this.initMap();
            
            // Pulisce i marker esistenti
            this.markerGroup.clearLayers();

            const customIcons = @js($icons);
            const configDefault = @js($defaultIcon);

            locations.forEach(loc => {
                if (loc.lat && loc.lng) {
                    // Icona
                    let iconUrl = customIcons[loc.geolocatable_type] || configDefault.url;
                    const customIcon = L.icon({
                        iconUrl: iconUrl,
                        shadowUrl: configDefault.shadow,
                        iconSize: configDefault.size,
                        iconAnchor: configDefault.anchor,
                        popupAnchor: [1, -34]
                    });

                    // Nome
                    let displayName = (loc.geolocatable && loc.geolocatable.name) 
                        ? loc.geolocatable.name 
                        : loc.geolocatable_type.split('\\').pop();

                    // CREAZIONE MARKER E POPUP
                    // Importante: usiamo una stringa HTML semplice
                    const popupContent = `<b>${displayName}</b><br><small>ID: ${loc.geolocatable_id}</small>`;

                    L.marker([loc.lat, loc.lng], { icon: customIcon })
                        .bindPopup(popupContent) // Bind prima di aggiungere al gruppo
                        .addTo(this.markerGroup);
                }
            });

            // Adatta la vista solo se ci sono nuovi marker
            if (locations.length > 0) {
                // Usiamo un piccolo timeout per evitare conflitti di rendering
                setTimeout(() => {
                    const bounds = locations.map(l => [l.lat, l.lng]);
                    this.map.fitBounds(bounds, { padding: [50, 50], maxZoom: 15 });
                }, 100);
            }
        }
     }"
     x-effect="if($wire.readyToLoad) { renderMarkers(@js($locations)) }">

    <div id="global-map" 
         wire:ignore 
         style="height: 600px; width: 100%; border: 1px solid #ddd; border-radius: 8px; background: #f8f9fa;">
        
        <div x-show="!$wire.readyToLoad" 
             style="display: flex; justify-content: center; align-items: center; height: 100%; font-family: sans-serif; color: #555;">
            <div>
                <span style="display: block; text-align: center; font-size: 2rem; margin-bottom: 10px;">🌍</span>
                Caricamento dei modelli sulla mappa...
            </div>
        </div>
    </div>

    @once
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endonce
</div>