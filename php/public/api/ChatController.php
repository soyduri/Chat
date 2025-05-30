<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type");
// header("Content-Type: application/json");
require_once("../../modelo/Usuarios.php");
//Controlador de toda la interfaz del chat.
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $usuario = Usuarios::getUsuarioById($id); 
    echo json_encode($usuario[0]); 
}
