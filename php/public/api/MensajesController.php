<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");

require_once("../../modelo/Usuarios.php");
//Para obtener los mensajes del que coincidan entre ids
if (isset($_GET['id_emisor']) && isset($_GET['id_receptor'])) {
    $id_emisor = $_GET['id_emisor'];
    $id_receptor = $_GET['id_receptor'];
    $mensajes = Usuarios::getMensajesByEmisorReceptor($id_emisor, $id_receptor);
    echo json_encode($mensajes);
    exit;
}
