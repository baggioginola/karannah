<?php

/**
 * Created by PhpStorm.
 * User: mario.cuevas
 * Date: 9/23/2016
 * Time: 9:22 AM
 */

require_once CLASSES . 'CEncryption.class.inc.php';


class Session
{
    private static $object = null;

    public static function singleton()
    {
        if (!isset(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    public function store($token)
    {
        $_SESSION['token'] = $token;
    }

    public function storeUserInfo($name = '', $last_name = '', $e_mail, $phone)
    {
        $_SESSION['name'] = $name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['e_mail'] = $e_mail;
        $_SESSION['phone'] = $phone;
    }

    public function destroy()
    {
        if (!isset($_SESSION['token'])) {
            return false;
        }

        if (isset($_SESSION['name'])) {
            unset($_SESSION['name']);
        }

        if (isset($_SESSION['last_name'])) {
            unset($_SESSION['last_name']);
        }

        if (isset($_SESSION['e_mail'])) {
            unset($_SESSION['e_mail']);
        }

        if (isset($_SESSION['phone'])) {
            unset($_SESSION['phone']);
        }

        unset($_SESSION['token']);
        return true;
    }

    public function validate()
    {
        if (!$token = $this->getToken()) {
            return false;
        }

        if (!$this->getArgs()) {
            return false;
        }

        list($name, $last_name) = $this->getArgs();

        if (!($token == Encryption::singleton()->generateToken($name, $last_name))) {
            return false;
        }

        return true;
    }

    private function getArgs()
    {
        if (!isset($_SESSION['name']) || !isset($_SESSION['last_name'])) {
            return false;
        }

        return array($_SESSION['name'], $_SESSION['last_name']);
    }

    public function getName()
    {
        if(isset($_SESSION['name'])) {
            return $_SESSION['name'];
        }
        return '';
    }

    public function getLastName()
    {
        if(isset($_SESSION['last_name'])) {
            return $_SESSION['last_name'];
        }
        return '';
    }

    public function getCustomerInfo()
    {
        if(!isset($_SESSION['name']) || !isset($_SESSION['last_name']) || !isset($_SESSION['e_mail']) || !isset($_SESSION['phone'])){
            return false;
        }

        return array('name' => $_SESSION['name'], 'last_name' => $_SESSION['last_name'], 'e_mail' => $_SESSION['e_mail'], 'phone' => $_SESSION['phone']);
    }
    private function getToken()
    {
        if (!isset($_SESSION['token'])) {
            return false;
        }
        return $_SESSION['token'];
    }
}