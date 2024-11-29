<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Conexión a la base de datos
$servername = "192.168.88.31";
$username = "root";
$password = "";
$dbname = "aptec";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el ID de la estación desde la solicitud GET
$id_est = isset($_GET['id_est']) ? intval($_GET['id_est']) : 0;

// Consulta para obtener los detalles de la estación y los últimos datos de `ub1` y `ub_2`
$sql = "
    SELECT e.id_est, e.Descr, 
        COALESCE(ub1_data.BattV, '') AS BattV, 
        COALESCE(ub1_data.TempAmb, '') AS TempAmb, 
        COALESCE(ub1_data.Pbar, '') AS Pbar, 
        COALESCE(ub2_data.PrecipP, '') AS PrecipP, 
        COALESCE(ub2_data.Rad, '') AS Rad, 
        COALESCE(ub2_data.RH, '') AS RH, 
        COALESCE(ub2_data.DirV, '') AS DirV, 
        GREATEST(COALESCE(ub1_data.timestamp, 0), COALESCE(ub2_data.timestamp, 0)) AS latest_timestamp
    FROM est e
    LEFT JOIN (
        SELECT id_est, BattV, TempAmb, Pbar, timestamp
        FROM ub1
        WHERE id_est = ? 
        ORDER BY timestamp DESC 
        LIMIT 1
    ) AS ub1_data ON e.id_est = ub1_data.id_est
    LEFT JOIN (
        SELECT id_est, PrecipP, Rad, RH, DirV, timestamp
        FROM ub_2
        WHERE id_est = ? 
        ORDER BY timestamp DESC 
        LIMIT 1
    ) AS ub2_data ON e.id_est = ub2_data.id_est
    WHERE e.id_est = ?;
";

// Preparar la declaración
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $id_est, $id_est, $id_est);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Cerrar la conexión
$stmt->close();
$conn->close();

// Devolver los datos en formato JSON
echo json_encode($data);
?>
