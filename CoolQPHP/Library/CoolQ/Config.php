<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/4
 * Time: 17:49
 */

namespace CoolQ;


class Config
{
    static public  $db ;
    static public function init(){
        include CORE_PATH . 'Conf/Config.php';
        self::$db = array(
            'dbHost'=>dbHost,
            'dbUser'=>dbUser,
            'dbPassword'=>dbPassword,
            'dbTable'=>dbTable,
            'dbPort'=>dbPort,
        );
    }

    static public function getDbConfig(){
        return self::$db;
    }
}