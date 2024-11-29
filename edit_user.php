<?php
include 'db.php';
session_start(); // Iniciar sesión aquí
$id = $_GET['id'] ?? 0;

if ($id) {
    $query = "SELECT * FROM user WHERE id_user = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch();
}

if (!$user) {
    echo "Usuario no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuario</title>
    <link rel="stylesheet" href="styles.css"> <!-- Incluye el CSS para el estilo -->
</head>
<body>

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<!-- Content page -->
<section class="full-box dashboard-contentPage">
    <?php include 'navbar.php'; ?>
    <h2>Modificar Usuario</h2>
    <form action="update_user.php" method="POST">
        <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($user['id_user']); ?>">

        <label for="nombres">Nombres:</label>
        <input type="text" name="nombres" value="<?php echo htmlspecialchars($user['nombres']); ?>" required><br>

        <label for="apel_pat">Apellido Paterno:</label>
        <input type="text" name="apel_pat" value="<?php echo htmlspecialchars($user['apel_pat']); ?>"><br>

        <label for="apel_mat">Apellido Materno:</label>
        <input type="text" name="apel_mat" value="<?php echo htmlspecialchars($user['apel_mat']); ?>"><br>

        <label for="cel">Celular:</label>
        <input type="text" name="cel" value="<?php echo htmlspecialchars($user['cel']); ?>" required><br>

        <label for="fec_nac">Fecha de Nacimiento:</label>
        <input type="date" name="fec_nac" value="<?php echo htmlspecialchars($user['fec_nac']); ?>" required><br>

        <label for="CI">CI (Documento de identificación):</label>
        <input type="text" name="CI" value="<?php echo htmlspecialchars($user['CI']); ?>" required><br>

        <label for="ubi">Ubicación:</label>
        <input type="text" name="ubi" value="<?php echo htmlspecialchars($user['ubi']); ?>"><br>

        <label for="pais">País:</label>
        <input type="text" name="pais" value="<?php echo htmlspecialchars($user['pais']); ?>"><br>

        <label for="ciud">Ciudad:</label>
        <input type="text" name="ciud" value="<?php echo htmlspecialchars($user['ciud']); ?>"><br>

        <label for="zona">Zona:</label>
        <input type="text" name="zona" value="<?php echo htmlspecialchars($user['zona']); ?>"><br>

        <label for="comp">Complemento:</label>
        <input type="text" name="comp" value="<?php echo htmlspecialchars($user['comp']); ?>"><br>

        <label for="id_cargo">Cargo:</label>
        <select name="id_cargo" required>
            <?php
            $stmt = $pdo->query("SELECT id_cargo, nom_cargo FROM cargo");
            while ($row = $stmt->fetch()) {
                $selected = $row['id_cargo'] == $user['id_cargo'] ? 'selected' : '';
                echo "<option value='{$row['id_cargo']}' $selected>{$row['nom_cargo']}</option>";
            }
            ?>
        </select><br>

        <label for="pass_user">Contraseña:</label>
        <input type="password" name="pass_user" value="<?php echo htmlspecialchars($user['pass_user']); ?>"><br>

        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

        <label for="inst">Institución o Empresa:</label>
        <input type="text" name="inst" value="<?php echo htmlspecialchars($user['inst']); ?>"><br>

        <label for="stat">Estado:</label>
        <select name="stat">
            <option value="1" <?php echo $user['stat'] == 1 ? 'selected' : ''; ?>>Activo</option>
            <option value="0" <?php echo $user['stat'] == 0 ? 'selected' : ''; ?>>Inactivo</option>
        </select><br>

        <button type="submit">Actualizar</button>
    </form>
</section>

<?php include 'notifications.php'; ?>
<?php include 'footer.php'; ?>

</body>
</html>
