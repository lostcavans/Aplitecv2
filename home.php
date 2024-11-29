<?php
session_start(); // Iniciar sesión
require 'db.php'; // Incluir la conexión a la base de datos

// Verificar si el usuario está logueado y tiene un cargo
if (!isset($_SESSION['id_user']) || !isset($_SESSION['id_cargo'])) {
    die("Acceso no autorizado.");
}

// Obtener el ID del cargo del usuario logueado
$id_cargo_usuario = $_SESSION['id_cargo'];

// Obtener todas las notificaciones activas (status_not = 1) del cargo del usuario
$stmt = $pdo->prepare("SELECT n.id_not, n.msg, n.date_create, n.date_end, n.target, n.status_not, c.nom_cargo, u.nombres, u.apel_pat, u.apel_mat 
                        FROM notification n 
                        JOIN cargo c ON n.target = c.id_cargo 
                        JOIN user u ON n.id_user = u.id_user  -- JOIN para obtener el nombre del usuario
                        WHERE n.status_not = 1 AND n.target = :id_cargo 
                        ORDER BY n.date_create DESC");
$stmt->execute([':id_cargo' => $id_cargo_usuario]);
$notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f5;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin: 15px;
            padding: 20px;
            width: 300px;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card h3 {
            margin: 0;
            color: #3498db;
        }
        .card p {
            margin: 10px 0;
            color: #555;
        }
        .card .date {
            font-size: 0.9em;
            color: #888;
        }
    </style>
</head>
<body>
<?php include 'header.php';?>
<?php include 'sidebar.php';?>
<section class="full-box dashboard-contentPage">
    <?php include 'navbar.php'; ?>
    <h1>Notificaciones/Avisos</h1>

    <div class="container">
        <?php if ($notificaciones): ?>
            <?php foreach ($notificaciones as $notificacion): ?>
                <div class="card">
                    <!--<h3><?php echo htmlspecialchars($notificacion['nom_cargo']); ?></h3>-->
                    <p><strong>Mensaje:</strong> <?php echo htmlspecialchars($notificacion['msg']); ?></p>
                    <p><strong>Creado por:</strong> <?php echo htmlspecialchars($notificacion['nombres'] . ' ' . $notificacion['apel_pat'] . ' ' . $notificacion['apel_mat']); ?></p>
                    <p class="date"><strong>Fecha de creación:</strong> <?php echo htmlspecialchars($notificacion['date_create']); ?></p>
                    <!--<p class="date"><strong>Fecha de cierre:</strong> <?php echo htmlspecialchars($notificacion['date_end']); ?></p>-->
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay notificaciones para mostrar.</p>
        <?php endif; ?>
    </div>
</section>

</body>
</html>
<?php include 'notifications.php'; ?>
<?php include 'footer.php'; ?>
