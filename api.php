<?php

include_once "Public/Config.php";
include_once "Public/DB.class.php";


$json = file_get_contents("php://input") ? file_get_contents("php://input") : '{"post_type":"message","message_type":"group","time":1494008393,"group_id":194233857,"user_id":1353693508,"anonymous":"","sub_type":"friend","anonymous_flag":"","message":"[CQ:at,qq=2093208406] [CQ:image,file=765F30418D4A8BD543FB94CBADCBB7D4.jpg,url=http://gchat.qpic.cn/gchatpic_new/1353693508/2184233857-2553815795-765F30418D4A8BD543FB94CBADCBB7D4/0]"}';
$data = json_decode($json, true);



?>