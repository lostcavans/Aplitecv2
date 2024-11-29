<?php
// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conectar a la base de datos
$servername = "66.94.116.235";
$username = "Janco";
$password = ""; // Cambia si es necesario
$dbname = "aptec";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Consultar datos de estaciones
$sql = "SELECT id_est, Latitud, Longitud, Descr FROM est"; 
$result = $conn->query($sql);

// Inicializar el array de estaciones
$stations = [];

// Verificar si hay resultados
if ($result) {
    if ($result->num_rows > 0) {
        // Obtener los resultados y llenar el array
        while ($row = $result->fetch_assoc()) {
            $stations[] = $row;
        }
        // Devolver los datos en formato JSON
        header('Content-Type: application/json');
        echo json_encode(['est' => $stations]);
    } else {
        // No se encontraron resultados
        header('Content-Type: application/json');
        echo json_encode(['error' => 'No results found']);
    }
} else {
    // En caso de error en la consulta
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Query failed: ' . $conn->error]);
}

// Cerrar conexión
$conn->close();
?>
