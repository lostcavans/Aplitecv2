<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Permitir los métodos HTTP necesarios
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Permitir los encabezados necesarios

// Parámetros de la consulta
$id_est = $_GET['id_est'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

// Conexión a la base de datos
$mysqli = new mysqli('66.94.116.235', 'Janco', '', 'aptec');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Consulta combinada para unir las tablas Ub1 y Ub_2 por id_est
$sql = "
     SELECT 
        ub1.id_est, 
        ub1.BattV, 
        ub1.TempAmb, 
        ub1.Pbar, 
        ub1.timestamp AS timestamp_ub1, 
        ub_2.PrecipP, 
        ub_2.Rad, 
        ub_2.RH, 
        ub_2.DirV, 
        ub_2.timestamp AS timestamp_ub2
    FROM 
        ub1 
    LEFT JOIN 
        ub_2 
    ON 
        ub1.id_est = ub_2.id_est 
        AND ub1.timestamp AND ub_2.timestamp
    WHERE 
        ub1.id_est = ?
        AND ub1.timestamp BETWEEN ? AND ?
";

// Preparar la consulta
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('iss', $id_est, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

// Obtener datos combinados de Ub1 y Ub_2
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id_est' => $row['id_est'],
        'BattV' => $row['BattV'],
        'TempAmb' => $row['TempAmb'],
        'Pbar' => $row['Pbar'],
        'timestamp_ub1' => $row['timestamp_ub1'],
        'PrecipP' => $row['PrecipP'],
        'Rad' => $row['Rad'],
        'RH' => $row['RH'],
        'DirV' => $row['DirV'],
        'timestamp_ub2' => $row['timestamp_ub2']
    ];
}

// Enviar los datos en formato JSON
echo json_encode($data);

// Cerrar conexiones y declaraciones
$stmt->close();
$mysqli->close();
?>
