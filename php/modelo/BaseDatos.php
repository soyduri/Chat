<?php
//ESTE MODELO NOS SIRVE YA PARA CONECTAR CON LAS BASES DE DATOS QUE QUERAMOS.
require_once("env.php");
class BaseDatos
{
    //NOS CREAMOS LA VARIABLE PARA MANEJAR LA CONEXION

    private static $pdo_conexion;

    //HACEMOS LA FUNCION PARA ESTABLECER LA CONEXION

    public static function getConection()
    {
        $host = $_ENV["DB_HOST"];
        $db_name = $_ENV["DB_NAME"];
        $user = $_ENV["DB_USER"];


        if (self::$pdo_conexion === null) {
            try {
                self::$pdo_conexion = new PDO("mysql:host=$host;dbname=$db_name", "$user");
                self::$pdo_conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error al conectar a la base de datos : " . $e->getMessage());
            }
        }
        return self::$pdo_conexion;
    }

    //CERRAMOS LA SESION CON ESTA FUNCION

    public static function closeConection()
    {
        self::$pdo_conexion = null;
    }
}
