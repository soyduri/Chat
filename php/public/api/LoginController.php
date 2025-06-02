<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type");
// header("Content-Type: application/json");

require_once("../../modelo/Usuarios.php");

if (!empty($_POST['nombre_usuario']) && !empty($_POST['contrasena_hash'])) {
    $nombre_usuario = $_POST['nombre_usuario'];
    $password_plano = $_POST['contrasena_hash'];

    $usuario = Usuarios::getUsuarioByNombre($nombre_usuario);

    if ($usuario) {
        $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
        $_SESSION['id'] = $usuario['id'];

        Usuarios::UpdateEstado($nombre_usuario, 1);
        echo json_encode([
            "success" => true,
            "message" => "Login correcto",
            "nombre_usuario" => $_SESSION['nombre_usuario'],
            "id" => $_SESSION['id']
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
