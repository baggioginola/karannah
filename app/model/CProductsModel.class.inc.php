<?php
/**
 * Created by PhpStorm.
 * User: mario.cuevas
 * Date: 7/18/2016
 * Time: 5:08 PM
 */
require_once CLASSES . 'CDatabase.class.inc.php';

class ProductsModel extends Database
{
    private static $object = null;
    private static $table = 'productos';

    /**
     * @return null|ProductsModel
     */
    public static function singleton()
    {
        if (is_null(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    /**
     * @return array|bool
     */
    public function getAll()
    {
        if (!$this->connect()) {
            return false;
        }
        $result_array = array();

        $query = "SELECT * FROM " . self::$table . " WHERE num_imagenes > 0;";

        if (!$result = $this->query($query)) {
            return false;
        }

        while ($row = $this->fetch_assoc($result)) {
            $result_array[] = $row;
        }

        return $result_array;
    }

    /**
     * @return array|bool
     */
    public function getRandomAll()
    {
        if (!$this->connect()) {
            return false;
        }
        $result_array = array();

        $query = "SELECT " . self::$table . ".id_producto, " . self::$table . ".id_categoria, " . self::$table . ".nombre,
        " . self::$table . ".descripcion,
        " . self::$table . ".detalles_tecnicos, " . self::$table . ".precio, " . self::$table . ".moneda,
        " . self::$table . ".codigo_interno, categorias.nombre as categoria, marcas.nombre as marca,
        marcas.descuento,iva, tipo_cambio.moneda, tipo_cambio.tipo_cambio
        FROM  " . self::$table . "
        INNER JOIN categorias
        ON " . self::$table . ".id_categoria = categorias.id_categoria
        INNER JOIN marcas
        ON " . self::$table . ".id_marca = marcas.id_marca
        INNER JOIN tipo_cambio
        ON " . self::$table . ".moneda = tipo_cambio.id_tipo_cambio
        WHERE categorias.id_categoria IN(12, 11, 5, 1, 3, 4)
        AND  " . self::$table . ".status = true
        AND  " . self::$table . ".num_imagenes > 0
        ORDER BY rand() LIMIT 6;";

        if (!$result = $this->query($query)) {
            return false;
        }

        while ($row = $this->fetch_assoc($result)) {
            $result_array[] = $row;
        }

        return $result_array;
    }

    /**
     * @param string $id
     * @return array|bool|null
     */
    public function getById($id = '')
    {
        if (empty($id)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        $result_array = array();

        $query = "SELECT " . self::$table . ".id, " . self::$table . ".nombre, productos.descripcion,
        " . self::$table . ".codigo_interno, " . self::$table . ".precio,categorias.nombre as categoria,iva, descuento, categorias.id as id_categoria 
            FROM  " . self::$table . "
            INNER JOIN categorias
             ON " . self::$table . ".id_categoria = categorias.id
            WHERE productos.id = '" . $id . "' AND " . self::$table . ".active = true;";


        if (!$result = $this->query($query)) {
            return false;
        }

        $this->close_connection();

        while ($row = $this->fetch_assoc($result)) {
            $result_array = $row;
        }

        return $result_array;
    }

    /**
     * @param string $id_category
     * @return array|bool
     */
    public function getByCategory($id_category = '')
    {
        if (empty($id_category)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        $result_array = array();

        $query = "SELECT " . self::$table . ".id, " . self::$table . ".nombre,
        " . self::$table . ".codigo_interno, " . self::$table . ".precio,categorias.nombre as categoria,iva, descuento
            FROM  " . self::$table . "
            INNER JOIN categorias
             ON " . self::$table . ".id_categoria = categorias.id
            WHERE productos.id_categoria = '" . $id_category . "' AND " . self::$table . ".active = true;";

        if (!$result = $this->query($query)) {
            return false;
        }

        $this->close_connection();

        while ($row = $this->fetch_assoc($result)) {
            $result_array[] = $row;
        }

        return $result_array;
    }

    public function updateLikes($id_product = null)
    {
        if (is_null($id_product)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        $query = "UPDATE " . self::$table . " SET likes = likes + 1 where id_producto = " . $id_product . ";";

        if (!$result = $this->query($query)) {
            return false;
        }

        return true;
    }

    public function updateSales($id_product = null, $value = null)
    {
        if (is_null($id_product) || is_null($value)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        $query = "UPDATE " . self::$table . " SET ventas = ventas + " . $value . " WHERE id_producto = " . $id_product . ";";

        if (!$result = $this->query($query)) {
            return false;
        }

        return true;
    }
}