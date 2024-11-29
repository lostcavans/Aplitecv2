<?php
session_start(); // Iniciar sesión aquí
?>


<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Station Data View</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.6.0/dist/jspdf.umd.min.js"></script>
    <style>
        /* Body */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-color: #f4f4f9;
    color: #333;
    height: 100vh;
    overflow-y: scroll;
    display: flex;
    flex-direction: column;
}

/* Navbar */
header {
    height: 60px; /* Altura del navbar */
    background-color: #007bff; /* Color del navbar */
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    z-index: 1000; /* Asegura que el navbar esté por encima del contenido */
}

/* Contenedor principal */
.container {
    display: flex;
    flex: 1;
    gap: 20px;
    padding: 20px;
    justify-content: center;
    min-height: calc(100vh - 120px); /* Ajusta para que no se solape con el footer y navbar */
    overflow-y: auto;
    padding-top: 80px; /* Espacio para el navbar */
}

/* Contenido principal */
.main-content {
    display: flex;
    width: calc(100% - 260px); /* Resta el ancho del sidebar */
    margin-left: 260px; /* Espacio para el sidebar */
    padding-bottom: 20px;
    overflow-y: auto;
}

/* Sidebar */
aside {
    width: 260px; /* Ancho del sidebar */
    position: fixed;
    top: 60px; /* Alineado debajo del navbar */
    left: 0;
    height: calc(100vh - 60px); /* Altura ajustada para que no se solape con el navbar */
    background-color: #f4f4f9;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
}

/* Estilo de los filtros */
.filters {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-right: 10px;
}

.filters h3 {
    margin-top: 0;
    color: #555;
}

.filters label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.filters input {
    width: calc(100% - 20px);
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 10px;
    font-size: 14px;
}

.filters button {
    display: block;
    width: 100%;
    padding: 12px;
    margin-bottom: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.filters button:hover {
    background-color: #0056b3;
}

/* Gráficos */
.charts {
    flex: 2;
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding-bottom: 20px;
}

canvas {
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Tabla de datos */
table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #007bff;
    color: #fff;
}

tr:hover {
    background-color: #f1f1f1;
}

/* Estilo de datos de estación */
.station-data {
    margin-left: 260px; /* Espacio para el sidebar */
    width: calc(100% - 260px); /* Ajusta para que no se cubra con el sidebar */
    padding-bottom: 20px;
}

    </style>
</head>
<body>
<?php
// index.php
include 'header.php';
include 'sidebar.php';
?>
    
    <section class="full-box dashboard-contentPage">
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="filters">
            <h3>Filtros de Fecha</h3>
            <label for="startDate">Fecha de Inicio:</label>
            <input type="date" id="startDate">
            <label for="endDate">Fecha de Fin:</label>
            <input type="date" id="endDate">
            <button onclick="updateCharts()">Filtrar</button>

            <!-- Botones para filtrado rápido -->
            <button onclick="filterLastDay()">Último Día</button>
            <button onclick="filterLastWeek()">Última Semana</button>
            <button onclick="filterLastMonth()">Último Mes</button>
            
            <!-- Botón para descargar PDF -->
            <button onclick="downloadPDF()">Descargar PDF</button>
            
            <!-- Botón para descargar XML -->
            <button onclick="downloadXML()">Descargar XML</button>

             <!-- Botón para volver al mapa -->
             <button onclick="window.location.href='map.php'">Volver al Mapa</button>
        </div>
        <div class="charts">
            <canvas id="battVChart"></canvas>
            <canvas id="tempAmbChart"></canvas>
        </div>
    </div>
    <h2>Datos de la Estación</h2>
    <table id="dataTable">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Voltaje de Batería (BattV)</th>
                <th>Temperatura Ambiental (TempAmb)</th>
            </tr>
        </thead>
        <tbody>
            <!-- Los datos se llenarán con JavaScript -->
        </tbody>
    </table>
    </section>
    <script>
        let battVChart, tempAmbChart;

        async function fetchStationData(id_est, startDate = '', endDate = '') {
            const response = await fetch(`get_station_data.php?id_est=${id_est}&start_date=${startDate}&end_date=${endDate}`);
            if (!response.ok) {
                console.error('Error al obtener datos de la estación');
                return [];
            }
            const data = await response.json();
            return data;
        }

        function createChart(ctx, labels, data, label) {
            return new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: '#40E0D0',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        borderWidth: 2,
                        pointRadius: 0, // Oculta los puntos en la gráfica
                        pointHitRadius: 0 // Asegura que los puntos no sean interactivos
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Fecha'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: label
                            }
                        }
                    }
                }
            });
        }

        async function initializeCharts() {
            const urlParams = new URLSearchParams(window.location.search);
            const id_est = urlParams.get('id_est');

            if (!id_est) {
                console.error('ID de estación no proporcionado');
                return;
            }

            const data = await fetchStationData(id_est);

            const fechas = data.map(entry => entry.fecha);
            const battVValues = data.map(entry => entry.BattV);
            const tempAmbValues = data.map(entry => entry.TempAmb);

            const battVCtx = document.getElementById('battVChart').getContext('2d');
            const tempAmbCtx = document.getElementById('tempAmbChart').getContext('2d');

            battVChart = createChart(battVCtx, fechas, battVValues, 'Voltaje de Batería (BattV)');
            tempAmbChart = createChart(tempAmbCtx, fechas, tempAmbValues, 'Temperatura Ambiental (TempAmb)');
            
            updateTable(data);
        }

        function updateTable(data) {
            const tableBody = document.getElementById('dataTable').querySelector('tbody');
            tableBody.innerHTML = '';

            data.forEach(row => {
                const newRow = tableBody.insertRow();
                newRow.insertCell().textContent = row.fecha;
                newRow.insertCell().textContent = row.BattV ?? 'N/A';
                newRow.insertCell().textContent = row.TempAmb ?? 'N/A';
            });
        }

        async function updateCharts() {
            const urlParams = new URLSearchParams(window.location.search);
            const id_est = urlParams.get('id_est');

            if (!id_est) {
                console.error('ID de estación no proporcionado');
                return;
            }

            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            const data = await fetchStationData(id_est, startDate, endDate);

            const fechas = data.map(entry => entry.fecha);
            const battVValues = data.map(entry => entry.BattV);
            const tempAmbValues = data.map(entry => entry.TempAmb);

            // Actualizar los datos de los gráficos
            if (battVChart) {
                battVChart.data.labels = fechas;
                battVChart.data.datasets[0].data = battVValues;
                battVChart.update();
            }

            if (tempAmbChart) {
                tempAmbChart.data.labels = fechas;
                tempAmbChart.data.datasets[0].data = tempAmbValues;
                tempAmbChart.update();
            }

            // Actualizar la tabla
            updateTable(data);
        }

        async function downloadPDF() {
            const canvas1 = await html2canvas(document.getElementById('battVChart'), { scale: 2 });
            const canvas2 = await html2canvas(document.getElementById('tempAmbChart'), { scale: 2 });

            const imgData1 = canvas1.toDataURL('image/png');
            const imgData2 = canvas2.toDataURL('image/png');

            // Obtener el contenido de la tabla
            const tableElement = document.getElementById('dataTable').outerHTML;

            const data = new FormData();
            data.append('imgData1', imgData1);
            data.append('imgData2', imgData2);
            data.append('tableHTML', tableElement);

            const response = await fetch('generate_pdf.php', {
                method: 'POST',
                body: data
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);

                // Obtener la fecha y hora actual en formato 'YYYY-MM-DD_HH-mm-ss'
                const now = new Date();
                now.setHours(now.getHours() - 4);
                const formattedDate = now.toISOString().slice(0, 19).replace('T', '_').replace(/:/g, '-');

                // Crear el nombre del archivo con la fecha y hora
                const filename = `Informe_Estacion_${formattedDate}.pdf`;

                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
            } else {
                console.error('Error al generar el PDF.');
            }
        }

        async function downloadXML() {
            const tableElement = document.getElementById('dataTable').outerHTML;

            const data = new FormData();
            data.append('tableHTML', tableElement);

            const response = await fetch('generate_xml.php', {
                method: 'POST',
                body: data
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);

                // Crear el nombre del archivo con la fecha
                const filename = `EstacionData_${new Date().toLocaleDateString('es-BO')}.xml`;

                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
            } else {
                console.error('Error al generar el XML.');
            }
        }

        // Funciones para filtrado rápido
        function filterLastDay() {
            const now = new Date();
            const lastDay = new Date(now.getTime() - (24 * 60 * 60 * 1000));

            document.getElementById('startDate').value = lastDay.toISOString().split('T')[0];
            document.getElementById('endDate').value = now.toISOString().split('T')[0];
            updateCharts();
        }

        function filterLastWeek() {
            const now = new Date();
            const lastWeek = new Date(now.getTime() - (7 * 24 * 60 * 60 * 1000));

            document.getElementById('startDate').value = lastWeek.toISOString().split('T')[0];
            document.getElementById('endDate').value = now.toISOString().split('T')[0];
            updateCharts();
        }

        function filterLastMonth() {
            const now = new Date();
            const lastMonth = new Date();
            lastMonth.setMonth(now.getMonth() - 1);

            document.getElementById('startDate').value = lastMonth.toISOString().split('T')[0];
            document.getElementById('endDate').value = now.toISOString().split('T')[0];
            updateCharts();
        }

        window.onload = initializeCharts;
    </script>
</body>
</html>
<?php include 'notifications.php'; ?>
<?php include 'footer.php'; ?>