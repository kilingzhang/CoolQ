<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/22
 * Time: 14:18
 */

include 'base.php';

use Library\Robot;
use CoolQSDK\CQ;

\Library\TimeTool::StartTime();


\Library\Glo::init();
if(\Library\Request::put() != null && $_Request = \Library\Request::put()){
    //CoolQ api
    $post_type = $_Request['post_type'];
    $message_type = $_Request['message_type'];
    $user_id = isset($_Request['user_id']) && array_key_exists('user_id',$_Request) ? $_Request['user_id'] : null;
    $group_id = isset($_Request['group_id']) && array_key_exists('group_id',$_Request) ? $_Request['group_id'] : null;
    $discuss_id = isset($_Request['discuss_id']) && array_key_exists('discuss_id',$_Request) ? $_Request['discuss_id'] : null;
    $message = $_Request['message'];
    $Robot = Robot::getInstance(1246002938);


    $PluginController = null;
    $post_type = $_Request['post_type'];
    if(!$Robot->getIsOnPlugin()){
        exit('{"block": true}');
    }


    switch ($post_type) {
        //收到消息
        case 'message':
            $message_type = $_Request['message_type'];
            switch ($message_type) {
                //私聊消息
                case "private":
                    $user_id = $_Request['user_id'];
                    $message = $_Request['message'];
                    //消息子类型，如果是好友则是 "friend"，
                    //如果从群或讨论组来的临时会话则分别是 "group"、"discuss"
                    //"friend"、"group"、"discuss"、"other"
                    $sub_type = $_Request['sub_type'];
                    if($Robot->getIsOnFriend()){
                        if(($Robot->getIsQqWhiteList() && MsgTool::inArray($user_id,$Robot->getQqWhiteList())) || (!$Robot->getIsQqWhiteList())){
                            if(($Robot->getIsQqBlackList() && !MsgTool::inArray($user_id,$Robot->getQqBlackList())) || (!$Robot->getIsQqBlackList())){
                                if(($Robot->getIsKeyword() && MsgTool::arrayItemIsInString($message,$Robot->getKeyword())) || (!$Robot->getIsKeyword())){
                                    if($Robot->getIsFollow()){
                                        $Robot->setIsSend(FOLLOW);
                                    }else{
                                        //runPlugin
                                        $Robot->setIsSend(ON);
                                    }
                                }
                            }
                        }
                    }
                    // {"reply":"message","block": true}
                    break;
                //群消息
                case "group":
                    $user_id = $_Request['user_id'];
                    $message = $_Request['message'];
                    $group_id = $_Request['group_id'];
                    //匿名用户显示名
                    $anonymous = $_Request['anonymous'];
                    //匿名用户 flag，在调用禁言 API 时需要传入
                    $anonymous_flag = $_Request['anonymous_flag'];
                    // {"reply":"message","block": true,"at_sender":true,"kick":false,"ban":false}
                    if($Robot->getIsOnGroup()){
                        if(($Robot->getIsGroupWhiteList() && MsgTool::inArray($group_id,$Robot->getGroupWhiteList())) || (!$Robot->getIsGroupWhiteList())){
                            if(($Robot->getIsGroupBlackList() && !MsgTool::inArray($group_id,$Robot->getGroupBlackList())) || (!$Robot->getIsGroupBlackList())){
                                if(($Robot->getIsQqBlackList() && !MsgTool::inArray($user_id,$Robot->getQqBlackList())) || (!$Robot->getIsQqBlackList())){
                                    if(($Robot->getIsAt() && MsgTool::inString(CQ::enAtCode($Robot->getQQ()),$message)) || (!$Robot->getIsAt())){
                                        if(($Robot->getIsKeyword() && MsgTool::arrayItemIsInString($message,$Robot->getKeyword())) || (!$Robot->getIsKeyword())){
                                            if($Robot->getIsReplyAt()){
                                                $Robot->setIsSend(REPLYAT);
                                            }else{
                                                if($Robot->getIsFollow()){
                                                    $Robot->setIsSend(FOLLOW);
                                                }else{
                                                    $Robot->setIsSend(ON);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    break;
                //讨论组消息
                case "discuss":
                    $discuss_id = $_Request['discuss_id'];
                    // {"reply":"message","block": true,"at_sender":true}
                    $Robot->sendDiscussMsg($discuss_id, MsgTool::deCodeHtml($message));
                    //todo
                    //以后再说吧

                    break;
            }
            break;
        //群、讨论组变动等非消息类事件
        case 'event':
            $event = $_Request['event'];
            switch ($event) {
                //群管理员变动
                case "group_admin":
                    //"set"、"unset"	事件子类型，分别表示设置和取消管理员
                    $sub_type = $_Request['sub_type'];
                    $group_id = $_Request['group_id'];
                    $user_id = $_Request['user_id'];
                    break;
                //群成员减少
                case "group_decrease":
                    //"leave"、"kick"、"kick_me"	事件子类型，分别表示主动退群、成员被踢、登录号被踢
                    $sub_type = $_Request['sub_type'];
                    $group_id = $_Request['group_id'];
                    $user_id = $_Request['user_id'];
                    $operator_id = $_Request['operator_id'];
                    break;
                //群成员增加
                case "group_increase":
                    //"approve"、"invite"	事件子类型，分别表示管理员已同意入群、管理员邀请入群
                    $sub_type = $_Request['sub_type'];
                    $group_id = $_Request['group_id'];
                    $user_id = $_Request['user_id'];
                    $operator_id = $_Request['operator_id'];
                    break;
                //群文件上传
                case "group_upload":
                    $group_id = $_Request['group_id'];
                    $user_id = $_Request['user_id'];
                    #字段名	数据类型	说明
                    #id	string	文件 ID
                    #name	string	文件名
                    #size	number	文件大小（字节数）
                    #busid	number	busid（目前不清楚有什么作用）
                    $file = $_Request['file'];
                    break;
                //好友添加
                case "friend_added":
                    $user_id = $_Request['user_id'];
                    break;
            }
            break;
        //加好友请求、加群请求／邀请
        case 'request':
            $request_type = $_Request['request_type'];
            switch ($request_type) {
                case "friend":
                    $user_id = $_Request['user_id'];
                    $message = $_Request['message'];
                    $flag = $_Request['flag'];
                    //{"block": true,"approve":true,"reason":"就是拒绝你 不行啊"}
                    break;
                case "group":
                    //"add"、"invite"	请求子类型，分别表示加群请求、邀请登录号入群
                    $sub_type = $_Request['sub_type'];
                    $group_id = $_Request['group_id'];
                    $user_id = $_Request['user_id'];
                    $message = $_Request['message'];
                    $flag = $_Request['flag'];
                    //{"block": true,"approve":true,"reason":"就是拒绝你 不行啊"}
                    break;
            }
            break;
        default:
            # code...
            break;
    }
    $Robot->sendPrivateMsg($user_id,\Library\TimeTool::EndTime()['time']);




}elseif(\Library\Request::get() != null && $_Request = \Library\Request::get()){
    //Admin api

}






//                                 _oo8oo_
//                                o8888888o
//                                88" . "88
//                                (| -_- |)
//                                0\  =  /0
//                              ___/'==='\___
//                            .' \\|     |// '.
//                           / \\|||  :  |||// \
//                          / _||||| -:- |||||_ \
//                          |   | \\\  -  /// |   |
//                          | \_|  ''\---/''  |_/ |
//                         \  .-\__  '-'  __/-.  /
//                       ___'. .'  /--.--\  '. .'___
//                     ."" '<  '.___\_<|>_/___.'  >' "".
//                   | | :  `- \`.:`\ _ /`:.`/ -`  : | |
//                   \  \ `-.   \_ __\ /__ _/   .-` /  /
//               =====`-.____`.___ \_____/ ___.`____.-`=====
//                                 `=---=`
//                         佛祖保佑         永无bug

