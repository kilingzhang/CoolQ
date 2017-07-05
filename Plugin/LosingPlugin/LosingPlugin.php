<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/7 0007
 * Time: 17:19
 */
use CoolQ\Plugin\Plugin;
use CoolQ\Robot\Robot;
use CoolQ\MsgTool;
use CoolQSDK\CQ;

class LosingPlugin extends Plugin
{
    public function Start(){
        global $Robot;
        global $data;
        self::$Plugin = get_class();
        $post_type = $data['post_type'];
        $message_type = $data['message_type'];
        $user_id = $data['user_id'];
        $message = $data['message'];
    }
}