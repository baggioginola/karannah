<?php

/**
 * Created by PhpStorm.
 * User: mario.cuevas
 * Date: 8/17/2016
 * Time: 12:19 PM
 */
class CFile
{
    private static $object = null;
    private $log = array();
    private $dir = null;

    public static function singleton()
    {
        if (!isset(self::$object)) {
            self::$object = new CFile();
        }
        return self::$object;
    }

    public function delete($path = null, $name = null)
    {
        if (empty($path) || empty($name)) {
            return false;
        }

        $files = glob($path . $name . '*.{jpg,png}', GLOB_BRACE);

        foreach ($files as $key) {
            if (!unlink($key)) {
                return false;
            }
        }
        return true;
    }

    public function checkFiles($path = null)
    {
        if (empty($path) || empty($name)) {
            return false;
        }

        $files = glob($path . 'tmpImage*.{jpg,png}', GLOB_BRACE);

        return $files;
    }

    public function rename($path = null, $name = null)
    {
        if (empty($path) || empty($name)) {
            return false;
        }

        $files = glob($path . 'tmpImage*.{jpg,png}', GLOB_BRACE);

        echo print_r($files, 1);

        foreach ($files as $key) {
            $index_extension = getIndexExtension($key);

            $index = getIndex($index_extension);

            echo $index . "\n";
            if ($index == 0) {
                $extension = getExtension($index_extension);
                echo $extension . "\n";
                if (!rename($key, $path . $name . "." . $extension)) {
                    return false;
                }
            } else {
                if (!rename($key, $path . $name . '_' . $index_extension)) {
                    return false;
                }
            }
        }

        return true;
    }
}