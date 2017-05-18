<?php
/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/5 0005
 * Time: 10:25
 */
include_once "Public/Config.php";
include_once "Public/Snoopy.class.php";
include_once "Public/CoolQ.class.php";
include_once "Public/MsgTool.php";

//echo "<pre>";

//$Cool = new CoolQ();
////$res = $Cool->sendPrivateMsg(1353693508,"测试发送私聊消息");
////$res = $Cool->sendGroupMsg(194233857,"测试发送群聊消息");
////$res = $Cool->sendGroupMsg(194233857,"[CQ:at,qq=1353693508] 测试发送群聊at消息");
////$res = $Cool->sendDiscussMsg('',"测试发送讨论组消息");
////$res = $Cool->setGroupKick(194233857,1353693508);
////$res = $Cool->setGroupBan(194233857,1353693508);
////$res = $Cool->setGroupBan(194233857,1353693508,0);
////$res = $Cool->setGroupWholeBan(194233857,0);
////$res = $Cool->setGroupWholeBan(194233857,1);
////$res = $Cool->setGroupAdmin(194233857,1353693508,true);
////$res = $Cool->setGroupSpecialTitle(194233857,1353693508,"xy宝宝的学长");
////$res = $Cool->setGroupCard(194233857,1353693508,"");
////$res = $Cool->getGroupMemberInfo(194233857,1353693508);
//
//echo $res;
$a = array(
    123123,1232131231,1232132131,123123,12321
);
var_dump(MsgTool::in_Array(12345555,$a));