<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/5
 * Time: 14:43
 */
use CoolQ\Plugin\Plugin;
use CoolQ\Robot\Robot;
use CoolQ\MsgTool;
use CoolQSDK\CQ;

class TulingPlugin extends Plugin
{

    public function Start()
    {
        global $Robot;
        global $data;
        self::$Plugin = get_class();
        $post_type = $data['post_type'];
        $message_type = $data['message_type'];
        $user_id = $data['user_id'];
        $message = $data['message'];
        switch ($post_type) {
            case "message":
                $url = "http://www.kilingzhang.com/Api/YiBao/api.php?role=" . Role . "&hash=" . Hash . "&user_id=$user_id&text=" . urlencode($message) . "&on=true";
                $json = file_get_contents($url);
                $res = json_decode($json, true);
                if (!empty($res) && $res['code'] != 0) {
                    $msg = addslashes($json);
                } else {
                    $msg = $res['data'];
                }
                switch ($message_type) {
                    case "private":
                        $sub_type = $data['sub_type'];
                        switch ($sub_type) {
                            case "friend":
                                $Robot->sendPrivateMsg($user_id, MsgTool::deCodeHtml(addslashes($msg)));
                                break;
                            case "group":

                                break;
                            case "discuss":

                                break;
                            case "other":

                                break;
                        }
                        break;
                    case "group":
                        $group_id = $data['group_id'];
                        $Robot->sendGroupMsg($group_id, MsgTool::deCodeHtml("[CQ:at,qq=$user_id] \n" . $msg));
                        break;

                    case "discuss":
                        $discuss_id = $data['discuss_id'];
                        $Robot->sendDiscussMsg($discuss_id, MsgTool::deCodeHtml($res['data']));
                        break;
                }

                break;
        }
        $this->setIntercept(true);
    }

}