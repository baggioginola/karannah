<?php
/**
 * Created by PhpStorm.
 * User: mario.cuevas
 * Date: 5/18/2016
 * Time: 3:53 PM
 */
define('TYPE_INT', 'int');
define('TYPE_FLOAT', 'float');
define('TYPE_ALPHA', 'alpha');
define('TYPE_PASSWORD', 'password');
define('TYPE_BOOLEAN', 'boolean');
define('TYPE_DATE', 'date');
define('TYPE_DATETIME', 'datetime');

class BaseController
{
    /**
     * @param array $parameters
     * @param array $validParameters
     * @return bool
     */
    protected function validateParameters($parameters = array(), $validParameters = array())
    {
        if (empty($parameters) or !is_array($parameters)) {
            return false;
        }

        foreach ($parameters as $name => $content) {
            if (!isset($validParameters[$name])) {
                return false;
            }

            if($name == 'contenido' || $name == 'descripcion' || $name == 'detalles_tecnicos' || $name == 'clave_alterna' || $name == 'codigo_interno'){
                continue;
            }

            if (strcasecmp($validParameters[$name], TYPE_BOOLEAN) == 0) {
                Debugger::add('preg_match', $name, false, __LINE__, __METHOD__);
                return true;
            } else if (!preg_match($this->getRegularExpresion($validParameters[$name]), $content)) {
                Debugger::add('preg_match', $name, false, __LINE__, __METHOD__);
                Debugger::add('preg_match', $content, false, __LINE__, __METHOD__);
                LogsController::store();
                return false;
            }
        }
        return true;
    }

    /**
     * @param int $status
     * @param string $message
     * @return array
     */
    protected function getResponse($status = STATUS_SUCCESS, $message = MESSAGE_SUCCESS, $data = array())
    {
        return array('status' => $status, 'message' => $message, 'data' => $data);
    }

    /**
     * @param $type
     * @return bool|string
     */
    private function getRegularExpresion($type)
    {
        switch ($type) {
            case TYPE_INT:
                $regex = '/^[\d]{1,14}$/';
                break;
            case TYPE_FLOAT:
                $regex = '/^(\d){1,14}.?(\d){0,14}/';
                break;
            case TYPE_ALPHA:
                $regex = '/^[a-z A-Z .\w-]+/';
                break;
            case TYPE_PASSWORD:
                $regex = '/^[\w\d\W]+/';
                break;
            case TYPE_DATETIME:
                $regex = '/^(20[0-9]{2}-(0[1-9]{1}|1[0-2]{1}))-(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[01]{1}) ([0-1]{1}[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}:[0-5]{1}[0-9]{1}$/';
                break;
            case TYPE_DATE:
                $regex = '/^(20[0-9]{2}-(0[1-9]{1}|1[0-2]{1}))-(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[01]{1})$/';
                break;
            default:
                return false;
                break;
        }
        return $regex;
    }
}