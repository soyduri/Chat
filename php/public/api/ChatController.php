<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type");
// header("Content-Type: application/json");
require_once("../../modelo/Usuarios.php");
//Controlador de toda la interfaz del chat.
// $json = file_get_contents('php://input');
// $request = json_decode($json,true);
$nombre_usuario = $_SESSION['nombre_usuario'];
if (isset($_POST['emisor_id']) && isset($_POST["receptor_id"]) && isset($_POST["contenido"])) {
    $emisor = $_POST['emisor_id'];
    $receptor = $_POST["receptor_id"];
    $contenido = $_POST["contenido"];
    $resultado = Usuarios::registrarMensaje($emisor, $receptor, $contenido);
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
}

//Para obtener los datos del usuario emisor.
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuario = Usuarios::getUsuarioById($id);
    //Devolvemos en un json lo que nos devuelve la api.
    echo json_encode($usuario[0]);
}

if (isset($_POST['cerrar_sesion'])) {
    Usuarios::UpdateEstado($nombre_usuario, 0);
    echo json_encode([
        "success" => true,
        "message" => "Sesion cerrada con exito",
        "id" => $_SESSION['id'],
        "status" => 0
    ]);
}
