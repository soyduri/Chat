<?php
require_once("BaseDatos.php");

class Usuarios
{
    //Registramos usuario en la tabla de usuarios
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
    //Guardamos los datos en la tabla estado_usuario
    public static function registrarUsuarioEstado($nombre_usuario)
    {
        $stmt = BaseDatos::getConection()->prepare("SELECT id FROM usuarios WHERE nombre_usuario = :nombre_usuario");
        $stmt->bindParam(":nombre_usuario", $nombre_usuario, PDO::PARAM_STR);
        $stmt->execute();
        $row =  $stmt->fetch();
        $stmt = BaseDatos::getConection()->prepare(
            "INSERT INTO estado_usuario (usuario_id, en_linea, ultima_conexion) VALUES (" . $row['id'] . ",0,null)"
        );
        return $stmt->execute() ? true : "Error al registrar el usuario.";
    }
    //Funcion para el login.
    public static function getUsuarioByNombre($nombre_usuario, $type_fetch = PDO::FETCH_ASSOC)
    {
        $stmt = BaseDatos::getConection()->prepare(
            "SELECT id, nombre_usuario, contrasena_hash FROM usuarios WHERE nombre_usuario = :nombre_usuario"
        );
        $stmt->bindParam(":nombre_usuario", $nombre_usuario, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch($type_fetch); // Solo un usuario
    }
    //Función para actualizar el estado de en linea o no.
    public static function UpdateEstado($nombre_usuario, $en_linea)
    {

        $stmt = BaseDatos::getConection()->prepare("SELECT id FROM usuarios WHERE nombre_usuario = :nombre_usuario");
        $stmt->bindParam(":nombre_usuario", $nombre_usuario, PDO::PARAM_STR);
        $stmt->execute();
        //La función fetch nos devuelve toda la fila  que coincidan con el select que hemos hecho la consulta
        $row =  $stmt->fetch();
        $stmt = BaseDatos::getConection()->prepare(
            "UPDATE estado_usuario SET en_linea = ($en_linea) where usuario_id =" . $row['id'] . ""
        );
        return $stmt->execute() ? true : "Error al actualizar el usuario.";
    }
    //Función para obtener todos los usuarios.
    public static function mostrarAllUsuarios($type_fetch = PDO::FETCH_OBJ)
    {
        $stmt = BaseDatos::getConection()->prepare("SELECT * from usuarios,estado_usuario where usuarios.id = estado_usuario.usuario_id  order by id asc");
        $stmt->execute();
        return $stmt->fetchAll($type_fetch);
    }
    public static function mostrarAllUsuariosConectados($type_fetch = PDO::FETCH_OBJ)
    {   //Consulta para obtener los usuarios conectados.
        $stmt = BaseDatos::getConection()->prepare("SELECT nombre_usuario FROM usuarios where id=(Select usuario_id from estado_usuario where en_linea=1);");
        $stmt->execute();
        return $stmt->fetchAll($type_fetch);
    }

    public static function getUsuarioById($id, $type_fetch = PDO::FETCH_OBJ)
    {
        $stmt = BaseDatos::getConection()->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll($type_fetch);
    }

    public static function registrarMensaje($emisor_id, $receptor_id, $contenido)
    {

        $stmt = BaseDatos::getConection()->prepare(
            "INSERT INTO mensajes(emisor_id, receptor_id,contenido) VALUES (:emisor_id, :receptor_id,:contenido)"
        );
        $stmt->bindParam(":emisor_id", $emisor_id, PDO::PARAM_INT);
        $stmt->bindParam(":receptor_id", $receptor_id, PDO::PARAM_INT);
        $stmt->bindParam(":contenido", $contenido, PDO::PARAM_STR);
        return $stmt->execute() ? true : "Error al registrar el mensaje.";
    }

    public static function getMensajesByEmisorReceptor($id_emisor, $id_receptor, $type_fetch = PDO::FETCH_OBJ)
    {   //Hacemos la consulta de manera que obtenga tanto los mensajes que envia uno y recibe el otro y viceversa;
        $sql = "SELECT * 
            FROM mensajes 
            WHERE (emisor_id = :id_emisor AND receptor_id = :id_receptor) 
               OR (emisor_id = :id_receptor AND receptor_id = :id_emisor)
            ORDER BY enviado_en ASC";

        $stmt = BaseDatos::getConection()->prepare($sql);
        $stmt->bindParam(":id_emisor", $id_emisor, PDO::PARAM_INT);
        $stmt->bindParam(":id_receptor", $id_receptor, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll($type_fetch);
    }
}
