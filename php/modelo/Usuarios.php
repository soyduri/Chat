<?php
require_once("BaseDatos.php");

class Usuarios
{
    public static function registrarUsuario($nombre_usuario, $password_plano)
    {

        $stmt = BaseDatos::getConection()->prepare("SELECT id FROM usuarios WHERE nombre_usuario = :nombre_usuario");
        $stmt->bindParam(":nombre_usuario", $nombre_usuario, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetch()) {
            return "El usuario ya está registrado.";
        }

        $stmt = BaseDatos::getConection()->prepare(
            "INSERT INTO usuarios(nombre_usuario, contrasena_hash) VALUES (:nombre_usuario, :password)"
        );
        $stmt->bindParam(":nombre_usuario", $nombre_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password_plano, PDO::PARAM_STR);
        return $stmt->execute() ? true : "Error al registrar el usuario.";
    }

    public static function registrarUsuarioEstado($nombre_usuario)
    {
        $stmt = BaseDatos::getConection()->prepare("SELECT id FROM usuarios WHERE nombre_usuario = :nombre_usuario");
        $stmt->bindParam(":nombre_usuario", $nombre_usuario, PDO::PARAM_STR);
        $stmt->execute();
        $row =  $stmt->fetch();
        $stmt = BaseDatos::getConection()->prepare(
            "INSERT INTO estado_usuario (usuario_id, en_linea, ultima_conexion) VALUES (".$row['id'].",0,null)"
        );
        return $stmt->execute() ? true : "Error al registrar el usuario.";
    }

    public static function getUsuarioByNombrePassword($nombre_usuario, $password_plano, $type_fetch = PDO::FETCH_OBJ)
    {

        $stmt = BaseDatos::getConection()->prepare(
            "SELECT nombre_usuario FROM usuarios WHERE nombre_usuario = :nombre_usuario AND contrasena_hash = :contrasena_hash"
        );
        $stmt->bindParam(":nombre_usuario", $nombre_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":contrasena_hash", $password_plano, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll($type_fetch);
    }
}
