<?php
include 'db.php'; // Archivo de conexión a la BD

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $nombres = $data['nombres'];
    $apel_mat = $data['apel_mat'];
    $apel_pat = $data['apel_pat'];
    $CI = $data['CI'];
    $cel = $data['cel'];
    $fec_nac = $data['fec_nac'];
    $ubi = $data['ubi'];
    $pais = $data['pais'];
    $ciud = $data['ciud'];
    $zona = $data['zona'];
    $comp = $data['comp'];
    $id_cargo = $data['id_cargo'];
    $email = $data['email'];
    $pass_user = password_hash($data['pass_user'], PASSWORD_BCRYPT);
    $inst = $data['inst'];

    $query = "INSERT INTO usuarios (nombres, apel_mat, apel_pat, CI, cel, fec_nac, ubi, pais, ciud, zona, comp, id_cargo, email, pass_user, inst, stat) 
              VALUES ('$nombres', '$apel_mat', '$apel_pat', '$CI', '$cel', '$fec_nac', '$ubi', '$pais', '$ciud', '$zona', '$comp', '$id_cargo', '$email', '$pass_user', '$inst', 1)";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["status" => "success", "message" => "Usuario registrado"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al registrar"]);
    }
}
?>
