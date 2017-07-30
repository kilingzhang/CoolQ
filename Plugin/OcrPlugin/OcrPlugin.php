<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/7 0007
 * Time: 18:08
 */
use Library\Plugin;
use CoolQSDK\CQ;

class OcrPlugin extends Plugin
{

    public function Start()
    {
        global $_Request;
        global $Robot;
        $post_type = $_Request['post_type'];
        $message_type = $_Request['message_type'];
        $user_id = $_Request['user_id'];
        $message = $_Request['message'];
        switch ($post_type) {
            case "message":
                $message = trim($message);
                preg_match_all("/.*?\[CQ:image,file=.*?,url=(.*?)\]/", $message, $is);
                if (count($is) >= 2 && !empty($is[1])) {
                    $this->setIntercept(true);
                    $msg = $this->getOcrMessage($is[1][0]);
                    switch ($message_type) {
                        case "private":
                            $sub_type = $_Request['sub_type'];
                            switch ($sub_type) {
                                case "friend":
                                    break;
                                case "group":
                                    break;
                                case "discuss":
                                    break;
                                case "other":
                                    break;
                            }
                            $Robot->sendPrivateMsg($user_id, $msg);
                            break;
                        case "group":
                            $group_id = $_Request['group_id'];
                            $Robot->sendGroupMsg($group_id, $msg,$user_id);
                            break;
                        case "discuss":
                            $discuss_id = $_Request['discuss_id'];
                            $Robot->sendDiscussMsg($discuss_id, $msg);
                            break;
                    }
                }
                break;
        }
    }

    private function getOcrMessage($message)
    {
        $url = "http://www.kilingzhang.com/Api/Ocr/api.php?url=" . $message;
        $json = file_get_contents($url);
        $res = json_decode($json, true);
        if ($res['success'] != 1) {

        } else {
            $res = $res['result'];
            $msg = "---------嘿--------  ~\n";
            foreach ($res as $item){
                $msg .= $item['content'];
            }
        }
        return $msg;
    }

}