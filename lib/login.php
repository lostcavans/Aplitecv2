<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json');

// Configuración de conexión a la base de datos
$servername = "192.168.88.31"; // Cambia esto por tu servidor
$username = "root"; // Cambia esto por tu usuario de MySQL
$password = ""; // Cambia esto por tu contraseña de MySQL
$dbname = "aptec"; // Cambia esto por tu nombre de base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(array("status" => "error", "message" => "Connection failed: " . $conn->connect_error)));
}

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass_user = $_POST['pass_user'];

    // Consultar en la base de datos
    $sql = "SELECT * FROM user WHERE email = '$email' AND pass_user = '$pass_user' AND stat = 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode(array("status" => "success", "user" => $user));
    } else {
        echo json_encode(array("status" => "error", "message" => "Invalid email or password"));
    }
}

$conn->close();
?>
