<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/4
 * Time: 22:51
 */
use  CoolQSDK\CoolQSDK;
use CoolQSDK\MsgTool;
use CoolQSDK\CQ;
use CoolQ\Plugin\Plugin;
use CoolQ\Log;



$data = \CoolQ\Request::put();

$post_type = $data['post_type'];
$message_type = $data['message_type'];
$user_id = isset($data['user_id']) && array_key_exists('user_id',$data) ? $data['user_id'] : null;
$group_id = isset($data['group_id']) && array_key_exists('group_id',$data) ? $data['group_id'] : null;
$discuss_id = isset($data['discuss_id']) && array_key_exists('discuss_id',$data) ? $data['discuss_id'] : null;
$message = $data['message'];
global  $Robot;
Log::Info('上报事件:' . json_encode($data,JSON_UNESCAPED_UNICODE),get_class());
$Robot = \CoolQ\Robot\Robot::getInstance('127.0.0.1',5700,'slight');
$Robot->getRobotInfo();
switch ($post_type) {

    //收到消息
    case 'message':
        $message_type = $data['message_type'];

        switch ($message_type) {
            //私聊消息
            case "private":
                $user_id = $data['user_id'];
                $message = $data['message'];
                //消息子类型，如果是好友则是 "friend"，
                //如果从群或讨论组来的临时会话则分别是 "group"、"discuss"
                //"friend"、"group"、"discuss"、"other"
                $sub_type = $data['sub_type'];

                // {"reply":"message","block": true}
                $Robot->sendPrivateMsg($user_id, MsgTool::deCodeHtml($message));


                break;
            //群消息
            case "group":
                $user_id = $data['user_id'];
                $message = $data['message'];
                $group_id = $data['group_id'];
                //匿名用户显示名
                $anonymous = $data['anonymous'];
                //匿名用户 flag，在调用禁言 API 时需要传入
                $anonymous_flag = $data['anonymous_flag'];
                // {"reply":"message","block": true,"at_sender":true,"kick":false,"ban":false}





                break;
            //讨论组消息
            case "discuss":
                $discuss_id = $data['discuss_id'];
                // {"reply":"message","block": true,"at_sender":true}
                $Robot->sendDiscussMsg($discuss_id, MsgTool::deCodeHtml($message));



                break;
        }


        break;

    //群、讨论组变动等非消息类事件
    case 'event':
        $event = $data['event'];
        switch ($event) {
            //群管理员变动
            case "group_admin":
                //"set"、"unset"	事件子类型，分别表示设置和取消管理员
                $sub_type = $data['sub_type'];
                $group_id = $data['group_id'];
                $user_id = $data['user_id'];


                break;
            //群成员减少
            case "group_decrease":
                //"leave"、"kick"、"kick_me"	事件子类型，分别表示主动退群、成员被踢、登录号被踢
                $sub_type = $data['sub_type'];
                $group_id = $data['group_id'];
                $user_id = $data['user_id'];
                $operator_id = $data['operator_id'];


                break;
            //群成员增加
            case "group_increase":
                //"approve"、"invite"	事件子类型，分别表示管理员已同意入群、管理员邀请入群
                $sub_type = $data['sub_type'];
                $group_id = $data['group_id'];
                $user_id = $data['user_id'];
                $operator_id = $data['operator_id'];


                break;
            //群文件上传
            case "group_upload":
                $group_id = $data['group_id'];
                $user_id = $data['user_id'];
                #字段名	数据类型	说明
                #id	string	文件 ID
                #name	string	文件名
                #size	number	文件大小（字节数）
                #busid	number	busid（目前不清楚有什么作用）
                $file = $data['file'];


                break;
            //好友添加
            case "friend_added":
                $user_id = $data['user_id'];

                break;

        }


        break;

    //加好友请求、加群请求／邀请
    case 'request':
        $request_type = $data['request_type'];
        switch ($request_type) {
            case "friend":
                $user_id = $data['user_id'];
                $message = $data['message'];
                $flag = $data['flag'];
                //{"block": true,"approve":true,"reason":"就是拒绝你 不行啊"}

                break;
            case "group":
                //"add"、"invite"	请求子类型，分别表示加群请求、邀请登录号入群
                $sub_type = $data['sub_type'];
                $group_id = $data['group_id'];
                $user_id = $data['user_id'];
                $message = $data['message'];
                $flag = $data['flag'];
                //{"block": true,"approve":true,"reason":"就是拒绝你 不行啊"}


                break;
        }

        break;


    default:
        # code...
        break;
}





