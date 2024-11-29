<?php
include 'db.php';

$id = $_POST['id_user'] ?? 0;
$nombres = $_POST['nombres'] ?? '';
$apel_pat = $_POST['apel_pat'] ?? '';
$apel_mat = $_POST['apel_mat'] ?? '';
$cel = $_POST['cel'] ?? '';
$fec_nac = $_POST['fec_nac'] ?? '';
$CI = $_POST['CI'] ?? '';
$ubi = $_POST['ubi'] ?? '';
$pais = $_POST['pais'] ?? '';
$ciud = $_POST['ciud'] ?? '';
$zona = $_POST['zona'] ?? '';
$comp = $_POST['comp'] ?? '';
$id_cargo = $_POST['id_cargo'] ?? '';
$pass_user = $_POST['pass_user'] ?? '';
$email = $_POST['email'] ?? '';
$inst = $_POST['inst'] ?? '';
$stat = $_POST['stat'] ?? 1;

if ($id) {
    $query = "UPDATE user SET nombres = :nombres, apel_pat = :apel_pat, apel_mat = :apel_mat, 
              cel = :cel, fec_nac = :fec_nac, CI = :CI, ubi = :ubi, pais = :pais, ciud = :ciud, 
              zona = :zona, comp = :comp, id_cargo = :id_cargo, pass_user = :pass_user, email = :email, 
              inst = :inst, stat = :stat WHERE id_user = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'nombres' => $nombres,
        'apel_pat' => $apel_pat,
        'apel_mat' => $apel_mat,
        'cel' => $cel,
        'fec_nac' => $fec_nac,
        'CI' => $CI,
        'ubi' => $ubi,
        'pais' => $pais,
        'ciud' => $ciud,
        'zona' => $zona,
        'comp' => $comp,
        'id_cargo' => $id_cargo,
        'pass_user' => $pass_user,
        'email' => $email,
        'inst' => $inst,
        'stat' => $stat,
        'id' => $id
    ]);

    header('Location: list_users.php'); // Redirigir a la lista de usuarios
    exit;
} else {
    echo "Datos inválidos.";
    exit;
}
