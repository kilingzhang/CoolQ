<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/5 0005
 * Time: 14:39
 */

namespace CoolQ\Robot;

use CoolQSDK\CoolQSDK;
use CoolQ\Db\RobotSql;
use CoolQ\Config;

class Robot
{

    protected static $instance = null;
    private $path;
    private $CoolQ;
    private $QQ;
    private $manager;
    private $group_white_list;
    private $group_black_list;
    private $qq_white_list;
    private $qq_black_list;
    private $is_qq_white_list;
    private $is_qq_black_list;
    private $is_group_white_list;
    private $is_group_black_list;
    private $is_at;
    private $is_keyword;
    private $is_follow;
    private $keyword;
    private $is_on_friend;
    private $is_on_group;
    private $is_on_discuss;
    private $is_agree_friend;
    private $is_agree_group;
    private $status;

    private function __construct($HOST, $PORT, $TOKEN)
    {
        self::$instance = RobotSql::getInstance(Config::getDbConfig());
        $this->CoolQ = new CoolQSDK($HOST, $PORT, $TOKEN);
        $this->QQ = Config::getQQ();
//        $Robot = self::$instance->getRobot($this->QQ);
        \CoolQ\Debug::dump(self::$instance->setStatus($this->QQ,1));
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance($HOST, $PORT, $TOKEN)
    {
        // 检查对象是否已经存在，不存在则实例化后保存到$instance属性
        if (self::$instance == null) {
            self::$instance = new self($HOST, $PORT, $TOKEN);
        }
        return self::$instance;
    }

    /**
     * @param $plugin_class_name
     * @return null
     */
    public static function runPlugin($plugin_class_name, $getData, $Robot)
    {
        $Plugin = null;
//        echo $plugin_class_name . "<br>";
        include_once "Plugin/$plugin_class_name/" . $plugin_class_name . ".php";
        eval("@\$Plugin = new " . $plugin_class_name . "(\$getData,\$Robot);");
        if ($Plugin == null) return null;
        @$Plugin->Start();
        return @$Plugin;
    }


    public function getPluginOrders()
    {
        $sql = "SELECT coolq_plugin_list.name,coolq_plugin_orders.plugin_id,coolq_plugin_orders.order_name,coolq_plugin_list.priority,coolq_plugin_list.status,coolq_plugin_list.plugin_class FROM coolq_plugin_orders,coolq_plugin_list WHERE  coolq_plugin_orders.	plugin_id = coolq_plugin_list.id   ORDER BY  coolq_plugin_list.priority ASC";
        $rs = $this->DB->query($sql);
        $array = array();
        while ($r = $this->DB->getone($rs)) {
            $array[] = $r;
        }
        return $array;
    }




}