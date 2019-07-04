<?php
/**
 * Created by PhpStorm.
 * User: mario.cuevas
 * Date: 7/8/2016
 * Time: 4:33 PM
 */

require_once 'CBaseController.class.inc.php';
require_once CLASSES . 'CDir.class.inc.php';
require_once CLASSES . 'CFile.class.inc.php';

class VideosUpload extends BaseController
{
    public static $object = null;

    private $parameters = array();

    private $num_videos;

    private $name = '';
    private $id;

    /**
     * @return VideosUpload|null
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
    public function add()
    {
        Debugger::add('request', $_REQUEST, true, __LINE__, __METHOD__);
        if (!CDir::singleton()->setDir()) {
            Debugger::add('setDir', 'No Dir', false, __LINE__, __METHOD__);
            LogsController::store();
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }
        if (!$this->setName()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        if (!$this->setNumVideos()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        if (!$this->_setParameters()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        if (!$this->upload()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        LogsController::store();
        return json_encode($this->getResponse());
    }

    private function setNumVideos()
    {
        if (!isset($_REQUEST['num_videos']) || empty($_REQUEST['num_videos'])) {
            return false;
        }

        $this->num_videos = $_REQUEST['num_videos'];
        Debugger::add('setNumVideos', $this->num_videos, false, __LINE__, __METHOD__);
        return true;
    }

    private function setName()
    {
        if (!isset($_REQUEST['name']) || empty($_REQUEST['name'])) {
            return false;
        }

        $this->name = $_REQUEST['name'];
        Debugger::add('setName', $this->name, false, __LINE__, __METHOD__);
        return true;
    }

    /**
     * @return string
     */
    public function edit()
    {
        Debugger::add('request', $_REQUEST, true, __LINE__, __METHOD__);
        if (!$this->setName()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }
        if (!CDir::singleton()->setDir()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        if (!$this->_setParameters()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        if (!$this->upload()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }
        LogsController::store();
        return json_encode($this->getResponse());
    }

    public function rename()
    {
        if (!isset($_POST) || empty($_POST)) {
            return false;
        }

        if (!CDir::singleton()->setDir()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        $name = $_POST['name'];

        CFile::singleton()->rename(CDir::singleton()->getDir(), $name);
        return json_encode($this->getResponse());
    }

    /**
     * @return bool
     */
    private function upload()
    {
        if (empty($this->parameters)) {
            return false;
        }

        $dir = CDir::singleton()->getDir();

        ini_set('memory_limit', -1);
        foreach ($this->parameters as $parameter => $value) {
            if (!move_uploaded_file($this->parameters[$parameter]['tmp_name'], $dir . $this->parameters[$parameter]['name'])) {
                return false;
            }
            chmod($dir . $this->parameters[$parameter]['name'], 0777);
        }

        ini_restore('memory_limit');

        return true;
    }

    /**
     * @return bool
     */
    private function _setParameters()
    {
        if (!isset($_FILES) || empty($_FILES)) {
            return false;
        }

        $i = 1;
        foreach ($_FILES as $key => $value) {
            foreach ($value as $item => $val) {
                foreach ($val as $tmp => $name) {
                    if ($item == 'name') {
                        $ext = explode(".", $name);
                        $lastElement = sizeof($ext);

                        $extension = strtolower($ext[$lastElement - 1]);

                        if ($i == 1) {
                            $name = $this->name . "." . $extension;
                        } else {
                            $name = $this->name . "_" . $i . "." . $extension;
                        }
                        $this->parameters[$i]['extension'] = $extension;
                    }
                    $this->parameters[$i][$item] = $name;
                    $i++;
                }
                $i = 1;
            }
        }

        Debugger::add('setParameters', $this->parameters, true, __LINE__, __METHOD__);
        return true;
    }
}