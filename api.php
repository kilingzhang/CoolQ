<?php

include_once "Public/config.php";
include_once "Public/DB.class.php";

include_once "vendor/autoload.php";

use CoolQ\CoolQ;

$Q = new CoolQ('127.0.0.1',5700,'slight');
echo  $Q->sendPrivateMsg(1353693508,'test');

$json = file_get_contents("php://input") ? file_get_contents("php://input") : '{"post_type":"message","message_type":"group","time":1494008393,"group_id":194233857,"user_id":1353693508,"anonymous":"","sub_type":"friend","anonymous_flag":"","message":"[CQ:at,qq=2093208406] [CQ:image,file=765F30418D4A8BD543FB94CBADCBB7D4.jpg,url=http://gchat.qpic.cn/gchatpic_new/1353693508/2184233857-2553815795-765F30418D4A8BD543FB94CBADCBB7D4/0]"}';
$data = json_decode($json, true);



?>