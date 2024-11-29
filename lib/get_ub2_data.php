<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$servername = "http://66.94.116.235/";
$username = "Janco";
$password = "";
$dbname = "aptec";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id_est = $_GET['id_est'] ?? null;

if ($id_est) {
    $sql = "SELECT PrecipP, Rad, RH, DirV, VelV, timestamp FROM Ub_2 WHERE id_est = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_est);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
} else {
    echo json_encode(['error' => 'ID de estación no proporcionado']);
}

$conn->close();
?>

