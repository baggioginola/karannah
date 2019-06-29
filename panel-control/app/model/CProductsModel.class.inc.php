<?php
/**
 * Created by PhpStorm.
 * User: mario.cuevas
 * Date: 5/12/2016
 * Time: 9:53 AM
 */
require_once CLASSES . 'CDatabase.class.inc.php';

class ProductsModel extends Database
{
    private static $object = null;
    private static $table = 'productos';
    private static $key = 'id';

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

        $query = "SELECT " . self::$table . ".id, " . self::$table . ".nombre,
        " . self::$table . ".codigo_interno, " . self::$table . ".precio,categorias.nombre as categoria,iva 
            FROM  " . self::$table . "
            INNER JOIN categorias
             ON " . self::$table . ".id_categoria = categorias.id
            WHERE " . self::$table . ".active = true;";

        Debugger::add('categories', $query, false, __LINE__, __METHOD__);
        if (!$result = $this->query($query)) {
            return false;
        }

        while ($row = $this->fetch_assoc($result)) {
            $result_array[] = $row;
        }

        return $result_array;
    }

    public function getLastId()
    {
        if (!$this->connect()) {
            return false;
        }
        $result_array = array();

        $query = "SELECT MAX(id) as id FROM " . self::$table . " ";

        if (!$result = $this->query($query)) {
            return false;
        }

        while ($row = $this->fetch_assoc($result)) {
            $result_array = $row;
        }

        return $result_array;
    }

    public function getById($id = '')
    {
        if (empty($id)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        $result_array = array();

        $query = "SELECT " . self::$table . ".id, " . self::$table . ".id_categoria, " . self::$table . ".nombre,
        " . self::$table . ".descripcion,
        " . self::$table . ".precio,
        " . self::$table . ".codigo_interno, categorias.nombre as categoria,
        iva, num_imagenes, novedades, promociones 
            FROM  " . self::$table . "
            INNER JOIN categorias
             ON " . self::$table . ".id_categoria = categorias.id 
             WHERE " . self::$table . ".id = '" . $id . "' ";

        Debugger::add('categories', $query, false, __LINE__, __METHOD__);
        if (!$result = $this->query($query)) {
            return false;
        }

        $this->close_connection();

        while ($row = $this->fetch_assoc($result)) {
            $result_array = $row;
        }

        return $result_array;
    }


    public function getByCodigoInterno($id = '')
    {
        if (empty($id)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        $result_array = array();

        $query = "SELECT " . self::$table . ".id_producto, " . self::$table . ".id_categoria, " . self::$table . ".nombre,
        " . self::$table . ".descripcion,
        " . self::$table . ".detalles_tecnicos, " . self::$table . ".precio, " . self::$table . ".moneda,
        " . self::$table . ".codigo_interno
            FROM  " . self::$table . "
            WHERE codigo_interno = '" . $id . "' ";

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
     * @param array $data
     * @return bool|int|string
     */
    public function add($data = array())
    {
        if (empty($data)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        if (!$this->insert($data, self::$table)) {
            return false;
        }

        $user_id = $this->getLastId();

        $this->close_connection();

        return $user_id;
    }

    /**
     * @param array $data
     * @return bool|int|string
     */
    public function edit($data = array(), $id = '')
    {
        if (empty($data) || empty($id)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        if (!$result = $this->update($data, $id, self::$table, self::$key)) {
            return false;
        }

        $this->close_connection();

        return $result;
    }
}