<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 01/feb/2017
 * Time: 20:20
 */
require_once __CONTROLLER__ . 'CBaseController.class.inc.php';
require_once __MODEL__ . 'CArticlesModel.class.inc.php';

class Articles extends BaseController
{
    private static $object = null;

    private $parameters = array();

    private $log = array();

    private $validParameters = array(
        'id' => TYPE_INT,
        'titulo' => TYPE_ALPHA,
        'subtitulo' => TYPE_ALPHA,
        'contenido' => TYPE_ALPHA,
        'active' => TYPE_BOOLEAN,
        'fecha' => TYPE_DATE,
        'fecha_alta' => TYPE_DATE,
        'fecha_modifica' => TYPE_DATE,
    );

    /**
     * @return null|Articles
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
        if (!$result = ArticlesModel::singleton()->getAll()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }
        return json_encode(UTF8Converter($result));
    }

    /**
     * @return string
     */
    public function getLastId()
    {
        if (!$result = ArticlesModel::singleton()->getLastId()) {
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

        $result = ArticlesModel::singleton()->getById($this->parameters['id']);

        return json_encode(UTF8Converter($result));
    }

    /**
     * @return string
     */
    public function add()
    {
        if (!$this->_setParameters()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        $result = array();
        if (!$result['id'] = ArticlesModel::singleton()->add($this->parameters)) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        return json_encode($this->getResponse(STATUS_SUCCESS, MESSAGE_SUCCESS, $result));
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

        if (!ArticlesModel::singleton()->edit($this->parameters, $id)) {
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

        if (!ArticlesModel::singleton()->edit($this->parameters, $id)) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        return json_encode($this->getResponse());
    }

    public function addFakeData($data)
    {
        foreach ($data as $key => $value) {
            $this->parameters[$key] = $value;
        }

        $result = ArticlesModel::singleton()->add($this->parameters);

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
            if($key == 'fecha'){
                $this->parameters[$key] = dateInput($value);
            }
            else {
                $this->parameters[$key] = $value;
            }
        }
        return true;
    }
}