<?php 
namespace Src;

use \PDO;
use \PDOException;

class Conexion{
    protected static $conexion;

    public function __construct()
    {
        self::crearConexion();
    }

    protected static function crearConexion(){
        if(self::$conexion!=null)return;
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__."/../");
        $dotenv->load();

        $host = $_ENV['HOST'];
        $dbname = $_ENV['DBNAME'];
        $user = $_ENV['USER'];
        $pass = $_ENV['PASS'];
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $opciones = [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION];

        try{
            self::$conexion = new PDO($dsn, $user, $pass, $opciones);
        }catch(PDOException $ex){
            die("Error en crearConexion".$ex->getMessage());
        }
    }
}