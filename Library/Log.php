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
namespace Library;
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
        self::$db = MySql::getInstance(Config::getDbConfig());
    }
    public static function Info($msg, $detial, $source = 'CoolQ',  $QQ = '')
    {
        self::Log($msg,$detial,INFO,$source,$QQ);
    }
    public static function Warn($msg, $detial, $source = 'CoolQ',  $QQ = '')
    {
        self::Log($msg,$detial,WARN,$source,$QQ);
    }
    public static function Error($msg, $detial, $source = 'CoolQ',  $QQ = '')
    {
        self::Log($msg,$detial,ERROR,$source,$QQ);
    }
    public static function Debug($msg, $detial, $source = 'CoolQ',  $QQ = '')
    {
        self::Log($msg,$detial,DEBUG,$source,$QQ);
    }

    public static function Log($msg,$detial,$level,$source,$robot_qq){
        $time = TimeTool::NowTime();
        $sql = "INSERT INTO `coolq_log`(`msg`, `time`, `detial`, `level`, `source`, `robot_qq`) 
                  VALUES ('$msg','$time','$detial','$level','$source','$robot_qq')";
        return self::$db->query($sql);
    }

}