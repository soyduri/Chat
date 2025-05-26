<?php
// Activar visualización de errores (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Encabezados CORS (mínimos y seguros para desarrollo)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Incluir el modelo
require_once("../../modelo/Usuarios.php");

// Verificar datos del formulario
if (!empty($_POST['nombre_usuario']) && !empty($_POST['contrasena_hash'])) {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena_hash = hash("sha256", $_POST['contrasena_hash']);

    $resultado = Usuarios::registrarUsuario($nombre_usuario, $contrasena_hash);

    if ($resultado === true) {
        echo json_encode([
            "success" => true,
            "message" => "Registro exitoso"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => $resultado
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Faltan campos obligatorios"
    ]);
}
