<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 01/feb/2017
 * Time: 20:20
 */
require_once __CONTROLLER__ . 'CBaseController.class.inc.php';
require_once __MODEL__ . 'CCategoriesModel.class.inc.php';

class Categories extends BaseController
{
    private static $object = null;

    private $parameters = array();

    private $log = array();

    private $validParameters = array(
        'id' => TYPE_INT,
        'nombre' => TYPE_ALPHA,
        'active' => TYPE_BOOLEAN,
        'titulo' => TYPE_ALPHA,
        'subtitulo' => TYPE_ALPHA,
        'descripcion' => TYPE_ALPHA,
        'fecha_alta' => TYPE_DATE,
        'fecha_modifica' => TYPE_DATE
    );

    /**
     * @return null|Categories
     */
    public static function singleton()
    {
        if (is_null(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    /**
     * @return string
     */
    public function getAll()
    {
        if (!$result = CategoriesModel::singleton()->getAll()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }
        return json_encode(UTF8Converter($result));
    }

    /**
     * @return string
     */
    public function getLastId()
    {
        if (!$result = CategoriesModel::singleton()->getLastId()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }
        return json_encode($result);
    }

    /**
     * @return string
     */
    public function getById()
    {
        if (!$this->_setParameters()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        $result = CategoriesModel::singleton()->getById($this->parameters['id']);

        return json_encode(UTF8Converter($result));
    }

    /**
     * @return string
     */
    public function add()
    {
        if (!$this->_setParameters()) {
            echo 'setParameters fail';
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        if (!CategoriesModel::singleton()->add($this->parameters)) {
            echo 'add fail';
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        return json_encode($this->getResponse());
    }

    /**
     * @return string
     */
    public function edit()
    {
        if (!$this->_setParameters()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        $id = $this->parameters['id'];

        unset($this->parameters['id']);

        $this->parameters['fecha_modifica'] = date('Y-m-d H:i:s');
        if (!CategoriesModel::singleton()->edit($this->parameters, $id)) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        return json_encode($this->getResponse());
    }

    /**
     * @return string
     */
    public function delete()
    {
        if (!$this->_setParameters()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        $id = $this->parameters['id'];

        unset($this->parameters['id']);

        if (!CategoriesModel::singleton()->edit($this->parameters, $id)) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        return json_encode($this->getResponse());
    }

    public function addFakeData($data)
    {
        foreach ($data as $key => $value) {
            $this->parameters[$key] = $value;
        }

        $result = CategoriesModel::singleton()->add($this->parameters);

        return $result;
    }

    /**
     * @return bool
     */
    private function _setParameters()
    {
        if (!isset($_POST) || empty($_POST)) {
            return false;
        }

        if (!$this->validateParameters($_POST, $this->validParameters)) {
            return false;
        }

        foreach ($_POST as $key => $value) {
            $this->parameters[$key] = $value;
        }

        return true;
    }
}