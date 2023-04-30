<?php

namespace Src;

use \PDO;
use \PDOException;
use Src\Articulo as SrcArticulo;

class Articulo extends Conexion
{
    private int $id;
    private string $nombre;
    private string $descripcion;
    private float $pvp;
    private int $stock;
    private string $disponible;
    private string $categoria;

    public function __construct()
    {
        parent::__construct();
    }
    //---------------------------------CRUD------------------------------
    public function create()
    {
        $q = "insert into articulos(nombre, descripcion, pvp, stock, disponible, categoria) 
        values(:n, :de, :p, :s, :di, :c)";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':de' => $this->descripcion,
                ':p' => $this->pvp,
                ':s' => $this->stock,
                ':di' => $this->disponible,
                ':c' => $this->categoria,
            ]);
        } catch (PDOException $ex) {
            die("Error en create" . $ex->getMessage());
        }
        parent::$conexion = null;
    }
    public static function read($cat = null, $count = 20)
    {
        parent::crearConexion();
        if ($count <= 0) $count = 20;
        $q = ($cat!="BAZAR" && $cat!="ALIMENTACION") ? "select * from articulos order by nombre LIMIT $count" :
            "select * from articulos where categoria='$cat'";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en read" . $ex->getMessage());
        }
        parent::$conexion = null;
        $datos = ['HTTP_RESPONSE' => http_response_code()];
        $datos['AUTOR'] = "Daniel Calatrava González | 2ºDAW";
        $datos['TOTAL_ARTICULOS'] = $stmt->rowCount();
        if ($stmt->rowCount() == 0) {
            $datos['ARTICULO'] = "No se han encontrado artículos";
        }
        while ($fila = $stmt->fetch(PDO::FETCH_OBJ)) {
            $datos['ARTICULOS'][] = [
                'id' => $fila->id,
                'nombre' => $fila->nombre,
                'descripcion' => $fila->descripcion,
                'pvp' => $fila->pvp,
                'stock' => $fila->stock,
                'disponible' => $fila->disponible,
                'categoria' => $fila->categoria,
            ];
        }
        return json_encode($datos);
    }
    //---------------------------------FAKER-----------------------------
    private static function hayArticulos(): bool
    {
        parent::crearConexion();
        $q = "select id from articulos";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en hayArticulos" . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->rowCount();
    }

    public static function crearArticulos($cant)
    {
        parent::crearConexion();
        if (self::hayArticulos()) return;
        $faker = \Faker\Factory::create('es_ES');
        for ($i = 0; $i < $cant; $i++) {
            (new Articulo)->setNombre(ucfirst($faker->words(2, true)))
                ->setDescripcion($faker->text())
                ->setPvp($faker->randomFloat(2, 10, 9999))
                ->setStock($faker->numberBetween(0, 100))
                ->setDisponible($faker->randomElements(['SI', 'NO'])[0])
                ->setCategoria($faker->randomElements(['BAZAR', 'ALIMENTACION'])[0])
                ->create();
        }
    }
    //---------------------------------SETTERS---------------------------

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Set the value of descripcion
     *
     * @return  self
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Set the value of pvp
     *
     * @return  self
     */
    public function setPvp($pvp)
    {
        $this->pvp = $pvp;

        return $this;
    }

    /**
     * Set the value of stock
     *
     * @return  self
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Set the value of disponible
     *
     * @return  self
     */
    public function setDisponible($disponible)
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * Set the value of categoria
     *
     * @return  self
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;

        return $this;
    }
}
