<?php
require_once("BaseDatos.php");
class Usuarios
{
    // Funcion para registrar un usuario
    public static function registrarUsuario($nombre_usuario, $password)
    {
        $hash = hash("sha256", $password);
        //VERIFICAMOS SI EXISTE EL CORREO
        $stmt = BaseDatos::getConection()->prepare("SELECT id from usuarios where nombre_usuariogit =:nombre_usuario");
        $stmt->bindParam(":nombre_usuario", $nombre_usuario, PDO::PARAM_STR);
        $stmt->execute();
        // if ($stmt->fetch()) {
        //     return "<div class='w-25 m-auto mb-4 text-center alert alert-danger'>El correo ya esta registrado</div>";
        // }
        $stmt = BaseDatos::getConection()->prepare("INSERT INTO usuarios(nombre_usuario,contrasena_hash) values (:nombre_usuario,:password)");
        $stmt->bindParam(":nombre_usuario", $nombre_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":password", $hash, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return true;
        }
    }
    //Función para hacer el login de un usuario
    public static function getUsuarioByNombrePassword($nombre_usuario, $contrasenia_hash, $type_fetch = PDO::FETCH_OBJ)
    {
        $stmt = BaseDatos::getConection()->prepare("SELECT nombre_usuario , contrasenia_hash FROM usuarios where nombre_usuario = :nombre_usuario AND contrasenia_hash = :contrasenia_hash;");
        $stmt->bindParam(":nombre_usuario", $nombre_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":contrasenia_hash", $contrasenia_hash, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll($type_fetch);
    }
}
