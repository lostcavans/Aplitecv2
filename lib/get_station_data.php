<?php
// Configuración de la base de datos
$servername = "66.94.116.235";
$username = "Janco"; // Cambia según tu configuración
$password = ""; // Cambia según tu configuración
$dbname = "aptec"; // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta SQL para unir las tablas Ub1 y Ub_2 usando id_est
$sql = "SELECT Ub1.id_est, Ub1.Descr, Ub1.BattV, Ub1.TempAmb, Ub1.Pbar, Ub1.timestamp,
               Ub_2.PrecipP, Ub_2.Rad, Ub_2.RH, Ub_2.DirV
        FROM Ub1
        INNER JOIN Ub_2 ON Ub1.id_est = Ub_2.id_est"; // Cambia la consulta si es necesario
$result = $conn->query($sql);

$stations = [];

// Si hay resultados, los añadimos al array
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $stations[] = $row;
    }
}

// Cerrar la conexión
$conn->close();

// Devolver los datos como JSON
header('Content-Type: application/json');
echo json_encode($stations);
?>
