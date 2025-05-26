<?php
require_once("../modelo/Usuarios.php");
$error = "";
if (isset($_POST['nombre_usuario']) && isset($_POST['contrasena_hash'])) {
    $nombre_usuario = $_POST['nombre_usuario'];
    $pass = $_POST['contrasena_hash'];
    if (Usuarios:: getUsuarioByNombrePassword($nombre_usuario, $pass)) {
        $_SESSION['nombre_usuario'] = $nombre_usuario;
        $_SESSION['contrasena_hash'] = $pass;
        // header("Location: ../public/index.php");
        exit(); // Asegúrate de detener la ejecución después de redirigir
    } else {
        echo $error;
    }
} 