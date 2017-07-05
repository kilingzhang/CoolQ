<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/2/14
 * Time: 1:09
 * @method void Log($msg) static
 * @method void error($msg) static
 * @method void info($msg) static
 * @method void sql($msg) static
 * @method void notice($msg) static
 * @method void alert($msg) static
 */

namespace CoolQ;

use CoolQ\exception\ClassNotFoundException;

class Log
{


    // 日志信息
    protected static $log = [];
    // 配置参数
    protected static $config = [];

    // 日志写入驱动
    protected static $driver;

    // 当前日志授权key
    protected static $key;

    // 数据库
    protected static $db;


    /**
     * 日志初始化
     * @param array $config
     */
    public static function init($config = [])
    {
        self::$db = Db\MySql::getInstance(Config::getDbConfig());
    }


    public static function Info($msg, $source = 'CoolQ', $type = '应用', $QQ = '')
    {
        $msg = addslashes($msg);
        $source = addslashes($source);
        $type = addslashes($type);
        $QQ = $QQ == '' ? Config::getQQ() : $QQ;
        $sql = "insert into coolq_log (`msg`, `time`, `source`, `type`, `level`,`robot_qq`) 
                  VALUES ('$msg', '" . date('Y-m-d H:i:s', time()) . "' , '$source' , '$type' , " . INFO . ",'$QQ')";
        return self::$db->query($sql);
    }


    public static function Warn($msg, $source = 'CoolQ', $type = '应用', $QQ = '')
    {
        $msg = addslashes($msg);
        $source = addslashes($source);
        $type = addslashes($type);
        $QQ = $QQ == '' ? Config::getQQ() : $QQ;
        $sql = "insert into coolq_log (`msg`, `time`, `source`, `type`, `level`,`robot_qq`) 
                  VALUES ('$msg', '" . date('Y-m-d H:i:s', time()) . "' , '$source' , '$type' , " . WARN . ",'$QQ')";
        return self::$db->query($sql);
    }

    public static function Error($msg, $source = 'CoolQ', $type = '应用', $QQ = '')
    {
        $msg = addslashes($msg);
        $source = addslashes($source);
        $type = addslashes($type);
        $QQ = $QQ == '' ? Config::getQQ() : $QQ;
        $sql = "insert into coolq_log (`msg`, `time`, `source`, `type`, `level`,`robot_qq`) 
              VALUES ('$msg', '" . date('Y-m-d H:i:s', time()) . "' , '$source' , '$type' , " . ERROR . ",'$QQ')";
        return self::$db->query($sql);
    }

    public static function Debug($msg, $source = 'CoolQ', $type = '应用', $QQ = '')
    {
        $msg = addslashes($msg);
        $source = addslashes($source);
        $type = addslashes($type);
        $QQ = $QQ == '' ? Config::getQQ() : $QQ;
        $sql = "insert into coolq_log (`msg`, `time`, `source`, `type`, `level`,`robot_qq`) 
                  VALUES ('$msg', '" . date('Y-m-d H:i:s', time()) . "' , '$source' , '$type' , " . DEBUG . ",'$QQ')";
        return self::$db->query($sql);
    }


}
