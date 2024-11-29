<?php
session_start(); // Iniciar sesión aquí
include 'header.php';
include 'sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map of Stations</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Map container */
        #map {
            margin-left: 0px; /* Espacio para el sidebar */
            height: calc(100vh - 120px); /* Ajustar según la altura del navbar y el footer */
            flex: 0;
        }
    </style>
</head>
<body>
<section class="full-box dashboard-contentPage">
    <?php include 'navbar.php'; ?>
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map; // Declaración única aquí
        let markers = {};

        // Función para obtener los datos de las estaciones
        async function fetchStations() {
    try {
        const response = await fetch('http://66.94.116.235/Aptec2/InTec2.0/get_dataMap.php');
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
        }
        const data = await response.json();
        
        // Verificar si hay un error en la respuesta JSON
        if (data.est && Array.isArray(data.est)) {
            return data.est;
        } else {
            throw new Error('Invalid JSON structure');
        }
    } catch (error) {
        console.error('Error fetching stations:', error);
        alert('Error fetching stations: ' + error.message); // Muestra un mensaje de error
        return []; // Devuelve un array vacío para evitar errores en el mapa
    }
}


        // Función para crear un marcador en el mapa para cada estación
        function createOrUpdateMarker(station) {
    const { id_est, Latitud, Longitud, Descr } = station;

    const popupContent = `<b>${Descr}</b><br>Lat: ${Latitud}<br>Long: ${Longitud}`;

    if (markers[id_est]) {
        markers[id_est].setPopupContent(popupContent);
    } else {
        const marker = L.marker([Latitud, Longitud])
            .addTo(map)
            .bindPopup(popupContent);

        marker.on('mouseover', function() {
            this.openPopup();
        });

        marker.on('mouseout', function() {
            this.closePopup();
        });

        markers[id_est] = marker;
    }
}


        // Inicializar el mapa
        async function initializeMap() {
            try {
                const stations = await fetchStations();

                if (!map) {
                    map = L.map('map').setView([0, 0], 2); // Inicializa el mapa en vista mundial

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);
                }

                stations.forEach(station => {
                    createOrUpdateMarker(station);
                });
            } catch (error) {
                console.error('Error initializing map:', error);
            }
        }

        initializeMap(); // Llama la función para inicializar el mapa
    </script>
</section>
<?php include 'notifications.php'; ?>
<?php include 'footer.php'; ?>
</body>
</html>
