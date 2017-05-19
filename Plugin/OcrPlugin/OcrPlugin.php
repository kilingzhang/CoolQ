<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/7 0007
 * Time: 18:08
 */
class OcrPlugin extends Plugin
{

    public function Start()
    {
        $post_type = $this->getGetData()['post_type'];
        $message_type = $this->getGetData()['message_type'];
        $user_id = $this->getGetData()['user_id'];
        $message = $this->getGetData()['message'];
        $Robot = $this->getRobot();
        switch ($post_type) {
            case "message":
                $message = trim($message);
                preg_match_all("/.*?\[CQ:image,file=.*?,url=(.*?)\]/", $message, $is);
                if (count($is) >= 2 && !empty($is[1])) {
                    $this->setIntercept(true);
                    $msg = $this->getOcrMessage($is[1][0]);
                    switch ($message_type) {
                        case "private":
                            $sub_type = $this->getGetData()['sub_type'];
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
                            $group_id = $this->getGetData()['group_id'];
                            $Robot->sendGroupMsg($group_id, $msg);
                            break;
                        case "discuss":
                            $discuss_id = $this->getGetData()['discuss_id'];
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
        $data = json_decode($json, true);
        if ($data['success'] != 1) {

        } else {
            $data = $data['result'];
            $msg = "---------嘿--------  ~\n";
            foreach ($data as $item){
                $msg .= $item['content'];
            }
        }
        return $msg;
    }

}