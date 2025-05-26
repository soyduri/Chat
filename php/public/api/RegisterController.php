<?php
require_once("../modelo/Usuarios.php");
$error = "";
$confirmacion = "";
if (!empty($_POST['nombre_usuario']) && !empty($_POST['contrasena_hash'])) {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena_hash = hash("sha256", $_POST["contrasena_hash"]);
    $resultado = Usuarios::registrarUsuario($nombre_usuario, $contrasena_hash);
    if ($resultado === true) {
        // header("Location: ../public/login.php");
    } else {
        $error = $resultado;
    }
}
