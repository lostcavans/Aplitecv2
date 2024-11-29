<?php
// register_user_sol.php
include 'db.php'; // Incluye la conexión PDO

function calculateAge($birthDate) {
    $birthDate = new DateTime($birthDate);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;
    return $age;
}

// Validación de datos
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombres = strtoupper(trim($_POST['nombres']));
    $apel_pat = strtoupper(trim($_POST['apel_pat']));
    $apel_mat = strtoupper(trim($_POST['apel_mat']));
    $cel = strtoupper(trim($_POST['cel']));
    $fec_nac = $_POST['fec_nac'];
    $CI = strtoupper(trim($_POST['CI']));
    $pais = strtoupper(trim($_POST['pais']));
    $departamento = strtoupper(trim($_POST['departamento']));
    $provincia = strtoupper(trim($_POST['provincia']));
    $ciud = strtoupper(trim($_POST['ciud']));
    $zona = strtoupper(trim($_POST['zona']));
    $comp = strtoupper(trim($_POST['comp']));
    $id_cargo = intval($_POST['id_cargo']);
    $pass_user = trim($_POST['pass_user']);
    $email = trim($_POST['email']);
    $inst = strtoupper(trim($_POST['inst']));
    $stat = 1; // Estado activo por defecto

    // Verificar edad
    if (calculateAge($fec_nac) < 18 || calculateAge($fec_nac) > 98) {
        $errors[] = 'La edad debe estar entre 18 y 98 años.';
    }

    // Verificar si el CI, celular o email ya están registrados
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE CI = :CI OR cel = :cel OR email = :email");
    $stmt->execute([':CI' => $CI, ':cel' => $cel, ':email' => $email]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = 'CI, celular o email ya están registrados.';
    }

    // Si no hay errores, insertar en la base de datos
    if (empty($errors)) {
        $sql = "INSERT INTO user (nombres, apel_pat, apel_mat, cel, fec_nac, CI, pais, ciud, zona, comp, id_cargo, pass_user, email, inst, departamento, provincia, stat) 
                VALUES (:nombres, :apel_pat, :apel_mat, :cel, :fec_nac, :CI, :pais, :ciud, :zona, :comp, :id_cargo, :pass_user, :email, :inst, :departamento, :provincia, :stat)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombres' => $nombres,
            ':apel_pat' => $apel_pat,
            ':apel_mat' => $apel_mat,
            ':cel' => $cel,
            ':fec_nac' => $fec_nac,
            ':CI' => $CI,
            ':pais' => $pais,
            ':ciud' => $ciud,
            ':zona' => $zona,
            ':comp' => $comp,
            ':id_cargo' => $id_cargo,
            ':pass_user' => $pass_user,
            ':email' => $email,
            ':inst' => $inst,
            ':departamento' => $departamento,
            ':provincia' => $provincia,
            ':stat' => $stat
        ]);

        echo json_encode(['success' => true, 'message' => 'Usuario registrado con éxito.']);
    } else {
        echo json_encode(['success' => false, 'message' => implode(", ", $errors)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido.']);
}
?>
