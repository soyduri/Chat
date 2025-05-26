<?php
require_once("../modelo/Usuarios.php");
$error = "";
$confirmacion = "";
if (!empty($_POST['nombre']) & !empty($_POST['email']) && !empty($_POST['password'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = hash("sha256", $_POST["password"]);
    $resultado = Usuarios::registrarUsuario($nombre, $email, $password);
    if ($resultado === true) {
        header("Location: ../public/login.php");
    } else {
        $error = $resultado;
    }
} else {
    $error = "<div class ='w-50 m-auto mb-4 text-center alert alert-success'>Faltan registros por rellenar</div>";
}
