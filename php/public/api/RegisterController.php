<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once("../../modelo/Usuarios.php");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$response = [];

if (!empty($_POST['nombre_usuario']) && !empty($_POST['contrasena_hash'])) {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena_plana = $_POST['contrasena_hash']; // ✅ no hacemos hash aquí
    $contrasena_hasheada = hash('sha256', $contrasena_plana);

    $resultado = Usuarios::registrarUsuario($nombre_usuario, $contrasena_hasheada);

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
