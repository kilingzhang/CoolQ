<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/25
 * Time: 2:56
 */

namespace Library;


class TimeTool
{
    public static function NowTime(){
        return date('Y-m-d H:i:s',time());
    }

    public static function StartTime(){
        global $stime;
        $stime = microtime(true);
        return $stime;
    }

    public static function EndTime(){
        global $stime;
        $etime = microtime(true);
        return array(
            'stime'=>$stime,
            'etime'=>$etime,
            'time'=>$etime - $stime
        );
    }


}