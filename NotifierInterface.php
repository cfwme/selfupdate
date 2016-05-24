<?php

namespace yii2tech\selfupdate;

interface NotifierInterface
{
    /**
     * @param $message
     * @return bool
     */
    public function notifySuccess($message = null);

    /**
     * @param $message
     * @return bool
     */
    public function notifyFail($message = null);
}