<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 15.12.2015
 * Time: 13:14
 */

namespace app\components;

use app\components\CommonHelper;

class MessageStatus {
    public $status = false;
    public $msg = '';
    public $msgError = '';
    public $data = [];

    public function __construct(MessageStatus $statusObj = null) {
        if (!is_null($statusObj) && $statusObj instanceof MessageStatus) {
            $this->import($statusObj);
        }
    }

    public function import (MessageStatus $statusObj) {
        foreach (get_object_vars($statusObj) as $key => $value) {
            if($key == 'data') {
                //CommonHelper::er($this->$key);
                $this->$key = array_merge($this->$key, $value);
                //CommonHelper::er($value);
            }
            else {$this->$key = $value;}

        }
    }

    public function setFalse($msg) {
        $this->msgError .= $msg;
        $this->status = false;
        //CommonHelper::er($msg);
    }

    public function setTrue($msg) {
        $this->msg .= $msg;
        $this->status = true;
    }


}