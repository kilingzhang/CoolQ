<?php

/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/14
 * Time: 2:29
 */
$db = \CoolQ\Db\MySql::getInstance(\CoolQ\Config::getDbConfig());
$sql = 'select * from coolq_log ORDER BY  time DESC ';
$res = $db->query($sql);
if(mysqli_num_rows($res) > 0){
    \CoolQ\Reply::EchoReply($db->getAll($res));
}else{
    \CoolQ\Reply::EchoReply();
}