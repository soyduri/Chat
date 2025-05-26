<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once("../../modelo/Usuarios.php");

// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//     http_response_code(200);
//     exit();
// }

if (!empty($_POST['nombre_usuario']) && !empty($_POST['contrasena_hash'])) {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena_hasheada = hash('sha256', $_POST['contrasena_hash']); 

    if (Usuarios::getUsuarioByNombrePassword($nombre_usuario, $contrasena_hasheada)) {
        $_SESSION['nombre_usuario'] = $nombre_usuario;

        echo json_encode([
            "success" => true,
            "message" => "Login correcto"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Usuario o contraseña incorrectos"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos"
    ]);
}
