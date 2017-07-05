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

class MusicPlugin extends Plugin
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
                $msg = $this->getMusic($message);
                switch ($message_type) {
                    case "private":
                        $sub_type = $data['sub_type'];
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
                        $group_id = $data['group_id'];
                        $Robot->sendGroupMsg($group_id, $msg);
                        break;
                    case "discuss":
                        $discuss_id = $data['discuss_id'];
                        $Robot->sendDiscussMsg($discuss_id, $msg);
                        break;
                }

                break;
        }
        $this->setIntercept(true);
    }

    public function getMusic($message)
    {
        $NetEase = explode("网易音乐#", $message);
        $QQ = explode("QQ音乐#", $message);
        $XiaMi = explode("虾米音乐#", $message);
        $message = trim($message);
        $name = explode("#", $message);
        $name = trim($name[1]);
        if (count($NetEase) >= 2) {
            $json = file_get_contents("http://www.kilingzhang.com/Api/Music/search.php?name=$name&source=163");
            $type = "163";
            $json = json_decode($json, true);
            if ($json['code'] != 200) {
                $msg = "亲 网络不太好 重新查询下呗~~";
            } else {
                $id = $json['result']['songs'][0]['id'];
                $msg = "[CQ:music,type=$type,id=$id]";
            }
        } elseif (count($QQ) >= 2) {
            $json = file_get_contents("http://www.kilingzhang.com/Api/Music/search.php?name=$name&source=163");
            $type = "163";
            $json = json_decode($json, true);
            if ($json['code'] != 200) {
                $msg = "亲 网络不太好 重新查询下呗~~";
            } else {
                $id = $json['result']['songs'][0]['id'];
                $msg = "[CQ:music,type=$type,id=$id]";
            }
        } elseif (count($XiaMi) >= 2) {
            $json = file_get_contents("http://www.kilingzhang.com/Api/Music/search.php?name=$name&source=xiami");
            $type = "xiami";
            $json = json_decode($json, true);
            if ($json['code'] != 0) {
                $msg = "亲 网络不太好 重新查询下呗~~";
            } else {
                $id = $json['data'][0]['song_id'];
                $msg = "[CQ:music,type=$type,id=$id]";
            }
        }
        return $msg;
    }

}