<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Incluir modelo
require_once("../modelo/Usuarios.php");

$response = [];

if (!empty($_POST['nombre_usuario']) && !empty($_POST['contrasena_hash'])) {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena_hash = hash("sha256", $_POST["contrasena_hash"]);

    $resultado = Usuarios::registrarUsuario($nombre_usuario, $contrasena_hash);

    if ($resultado === true) {
        $response = [
            "success" => true,
            "message" => "Registro exitoso"
        ];
    } else {
        $response = [
            "success" => false,
            "message" => $resultado
        ];
    }
} else {
    $response = [
        "success" => false,
        "message" => "Faltan campos obligatorios"
    ];
}

echo json_encode($response);
