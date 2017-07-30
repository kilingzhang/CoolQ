<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/25
 * Time: 2:39
 */

namespace Library;
use Library\Robot;

class Plugin
{
    private static $PluginName = null;
    public $Intercept;

    public function __construct(){
        $this->Intercept = false;
    }
    /**
     * @param bool $bool
     */
    public function setIntercept($bool = true){
        $this->Intercept = $bool;
    }
    /**
     * @return bool
     */
    public function isIntercept(){
        return $this->Intercept;
    }

    public static function setPluginName($PluginName){
        self::$PluginName = $PluginName;
    }

    public static function getPluginName(){
        return self::$PluginName;
    }

    public static function initPlugin($dir){
        $list = \Library\File::dirNodeTree($dir);
        print_r($list);
    }


    /**
     * @param $plugin_class_name
     * @return null
     */



    public static function runOders($PluginOrders)
    {
        global $_Request;
        global $Robot;
        $PluginController = null;
        foreach ($PluginOrders AS $order) {
            $pro = explode($order['order_name'], $_Request['message']);
            if ((count($pro) >= 2 && $order['status']) || $order['order_name'] == '*') {
                $PluginController = Robot::runPlugin($order['plugin_class']);
                if ($PluginController != null && $PluginController->isIntercept() == true) {
                    echo '{"block": true}';
                    break;
                }
            }
        }
        return $PluginController;
    }

}