<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permite solicitudes desde cualquier origen
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Permite métodos específicos
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Permite encabezados específicos

$servername = "66.94.116.235";
$username = "Janco";
$password = "";
$dbname = "aptec";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id_est, Latitud, Longitud, Descr FROM est";
$result = $conn->query($sql);

$stations = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $stations[] = $row;
    }
}

echo json_encode($stations);

$conn->close();
?>
