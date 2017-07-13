<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/13
 * Time: 12:44
 */
use CoolQ\Robot\Robot;
use CoolQ\Reply;

$con = \CoolQ\Config::getCoolQConfig();
$Robot = \CoolQ\Robot\Robot::getInstance($con['host'], $con['port'], $con['token']);

$data = \CoolQ\Request::get();

$keys = array(
    'qq'=> 'QQ',
    'time'=> 'RobotCreateTime',
    'status'=> 'RobotStatus',
    'manager'=> 'RobotManager',
    'group_white_list'=> 'RobotGroupWhiteList',
    'group_black_list'=> 'RobotGroupBlackList',
    'qq_white_list'=> 'tRobotQqWhiteList',
    'qq_black_list'=> 'RobotQqBlackList',
    'keyword'=> 'RobotKeyword',
    'is_group_black_list'=> 'RobotIsGroupBlackList',
    'is_group_white_list'=> 'RobotIsGroupWhiteList',
    'is_qq_black_list'=> 'RobotIsQqBlackList',
    'is_qq_white_list'=> 'RobotIsQqWhiteList',
    'is_at'=> 'RobotIsAt',
    'is_keyword'=> 'RobotIsKeyword',
    'is_follow'=> 'RobotIsFollow',
    'is_on_friend'=> 'RobotIsOnFriend',
    'is_on_group'=> 'RobotIsOnGroup',
    'is_on_discuss'=> 'RobotIsOnDiscuss',
    'is_agree_friend'=> 'RobotIsAgreeFriend',
    'is_on_plugin'=> 'RobotIsOnPlugin',
    'is_agree_group'=> 'RobotIsAgreeGroup',
    'qq_refuse_reason'=> 'RobotQQRefuseReason',
    'group_refuse_reason'=> 'RobotGroupRefuseReason',
    'is_reply_at'=> 'RobotIsReplyAt',
);

global $_Action;


switch ($_Action) {
    case 'Get':
        $_Key = isset($_Param[3]) ? ucwords($_Param[3]) : "";
        if ($_Key != "") {
            $_Key = strtolower($_Key);
            eval("@\$reply = \$Robot->get". $keys[$_Key] . "();");
            Reply::EchoReply($reply);
        } else {
            Reply::EchoReply($Robot->getRobotInfo());
        }
        break;
    case "Set":
        $_Key = isset($_Param[3]) ? ucwords($_Param[3]) : "";
        $_Value = isset($_Param[4]) ? ucwords($_Param[4]) : "";
        if ($_Key != "") {
            $_Key = strtolower($_Key);
            eval("@\$reply = \$Robot->set". $keys[$_Key] . "($_Value);");
            Reply::EchoReply($reply);
        } else {
            Reply::EchoReply($Robot->setRobotInfo($_Value));
        }
        break;
    case "Delect":
        break;
}

