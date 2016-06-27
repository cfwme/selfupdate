<?php

namespace yii2tech\selfupdate;

use yii\base\Object;
use yii\helpers\StringHelper;

class SlackNotifier extends Object implements NotifierInterface
{
    /**
     * @var string API token. See https://api.slack.com/web for details
     */
    public $token;
    /**
     * @var string Slash prefixed channel name
     */
    public $channel;

    /**
     * @var string
     */
    public $projectName;

    /**
     * @var string
     */
    public $username = 'Deploy Bot';


    public function notifySuccess($message = null)
    {
        $this->postMessage($message ?: "*{$this->projectName}* successfully updated");
    }

    public function notifyFail($message = null)
    {
        $message = StringHelper::truncate($message, 300);
        $this->postMessage("*{$this->projectName}* update failed \n ```$message```");
    }

    /**
     * @param string $message The message to post into a channel
     * @return boolean
     */
    protected function postMessage($message)
    {
        $ch = curl_init("https://slack.com/api/chat.postMessage");
        $data = http_build_query([
            "token" => $this->token,
            "channel" => $this->channel,
            "text" => $message,
            "username" => $this->username,
            "icon_url" => "https://avatars.slack-edge.com/2016-04-27/38127042451_d81bf9dafdc98155a4c7_48.png",
            "mrkdwn" => true,
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}