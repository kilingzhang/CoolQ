<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/2/14
 * Time: 1:09
 */
class Log
{


    /**
     * @param $msg  log内容
     * @param int $type 0:info 1:debug 2:warn 3:Error 4:Fatal
     * @param $other
     */
    public static function InsertMsgLog($msg, $qq, $group = "", $discuss = "", $type = 0, $other = "", $event_type, $sub_type = "")
    {
        $DB = new DB(dbHost, dbUser, dbPassword, dbTable, dbport);
        $time = date("Y-m-d H:i:s", time());
        $sql = "insert into coolq_log (qq,`group`,msg,discuss,time,type,other,post_type,event_type,sub_type)
                    values ('$qq','$group','$msg','$discuss','$time','$type','$other','message','$event_type','$sub_type')";
        $DB->query($sql);
    }

    public static function InsertEventLog($qq, $group = "", $operator_id = "", $type = 0, $other = "", $event_type, $sub_type = "")
    {
        $DB = new DB(dbHost, dbUser, dbPassword, dbTable, dbport);
        $time = date("Y-m-d H:i:s", time());
        $sql = "insert into coolq_log (qq,`group`,operator_id,time,type,other,post_type,event_type,sub_type)
                    values ('$qq','$group','$operator_id','$time','$type','$other','event','$event_type','$sub_type')";
        $DB->query($sql);
    }

    public static function InsertRequestLog($msg, $flag, $qq, $group = "", $type = 0, $other = "", $event_type, $sub_type = "")
    {
        $DB = new DB(dbHost, dbUser, dbPassword, dbTable, dbport);
        $time = date("Y-m-d H:i:s", time());
        $sql = "insert into coolq_log (msg,flag,qq,`group`,time,type,other,post_type,event_type,sub_type)
                    values ('$msg','$flag','$qq','$group','$time','$type','$other','event','$event_type','$sub_type')";
        $DB->query($sql);
    }


}