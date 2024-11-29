<?php
session_start();
header('Content-Type: application/json');

// Incluir la conexión a la base de datos
require 'db.php';

// Obtener los datos enviados por POST
$email = $_POST['email'];
$password = $_POST['password'];

// Preparar la consulta para verificar el usuario
$sql = "SELECT * FROM user WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    // Comparar la contraseña proporcionada con la almacenada en la base de datos
    if ($password === $user['pass_user']) {
        // Guardar el ID del usuario, nombre completo y el ID del cargo en la sesión
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['id_cargo'] = $user['id_cargo']; // Asegúrate de que este campo exista en la tabla user
        $full_name = $user['nombres'] . ' ' . $user['apel_pat'] . ' ' . $user['apel_mat'];
        $_SESSION['full_name'] = $full_name;

        // Registrar la fecha y hora de inicio de sesión en la tabla reg_user
        $sql = "INSERT INTO reg_user (id_user, datetime) VALUES (?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user['id_user']]);

        // Respuesta exitosa
        $response = array(
            'status' => 'success',
            'full_name' => $full_name,
            'id_cargo' => $_SESSION['id_cargo'] // Opcional: devolver el id_cargo en la respuesta
        );
    } else {
        // Contraseña incorrecta
        $response = array(
            'status' => 'error',
            'message' => 'Credenciales incorrectas'
        );
    }
} else {
    // Usuario no encontrado
    $response = array(
        'status' => 'error',
        'message' => 'Credenciales incorrectas'
    );
}

echo json_encode($response);
?>
