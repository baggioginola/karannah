<?php
/**
 * Created by PhpStorm.
 * User: mario.cuevas
 * Date: 6/24/2016
 * Time: 4:46 PM
 */

require_once 'CBaseController.class.inc.php';
require_once __MODEL__ . 'CVideosModel.class.inc.php';

class Videos extends BaseController
{
    private static $object = null;

    private $parameters = array();
    private static $type = 'videos';
    private $validParameters = array(
        'id' => TYPE_INT
    );

    /**
     * @return Videos|null
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
        $result = VideosModel::singleton()->getAll();
        return $result;
    }

    public function getCategoriesById($id = null)
    {
        if (is_null($id)) {
            return false;
        }

        if ($result = VideosModel::singleton()->getCategoriesById($id)) {
            return $result;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getById($parameters)
    {
        if (!$this->_setParameters($parameters)) {
            return false;
        }

        if ($result = VideosModel::singleton()->getById($this->parameters['id'])) {
            return $result;
        }

        return false;
    }

    /**
     * @return bool
     */
    private function _setParameters($parameters)
    {
        if (!isset($parameters) || empty($parameters)) {
            return false;
        }

        if (!$this->validateParameters($parameters, $this->validParameters)) {
            return false;
        }

        foreach ($parameters as $key => $value) {
            $this->parameters[$key] = $value;
        }
        return true;
    }
}