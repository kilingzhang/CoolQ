<?php


use Library\Plugin;
use CoolQSDK\CQ;
/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/5
 * Time: 14:43
 */
class FilmPlugin extends Plugin
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
                $pro = explode('搜',$message);
                if(count($pro) >= 2){
                    $keyword = $pro[1];
                }else{
                    $keyword = $message;
                }
                $res = file_get_contents("http://api.pansou.com/search_new.php?callback=jQuery172042529061511397237_1500990957874&q=$keyword&p=1");
                $res = json_decode($res,true);
                $list = $res['list']['data'];
                $msg = "$keyword 的搜索结果： \n";
                for ($i = 0 ; $i< count($list)  && $i< 10 ; $i++){
                    $msg .= $i . ' : ' . $list[$i]['title'] . '  ' . $list[$i]['des'] . "\n\n" . $list[$i]['link'] . "\n\n" ;

                }
                switch ($message_type) {
                    case "private":
                        $sub_type = $_Request['sub_type'];
                        switch ($sub_type) {
                            case "friend":
                                $Robot->sendPrivateMsg($user_id, CQ::deCodeHtml(($msg)));
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
                        $group_id = $_Request['group_id'];
                        $Robot->sendGroupMsg($group_id, CQ::deCodeHtml($msg),$user_id);
                        break;

                    case "discuss":
                        $discuss_id = $_Request['discuss_id'];
                        $Robot->sendDiscussMsg($discuss_id, CQ::deCodeHtml($msg));
                        break;
                }

                break;
        }
        $this->setIntercept(true);
    }

}