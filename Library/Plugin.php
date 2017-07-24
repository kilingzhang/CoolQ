<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/25
 * Time: 2:39
 */

namespace Library;


class Plugin
{
    private static $PluginName = null;
    public $Intercept;

    public function __construct(){
        $this->Intercept = false;
        self::setPluginName();
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

    private static function setPluginName(){
        self::$PluginName = get_class();
    }

    public static function getPluginName(){
        return self::$PluginName;
    }

    /*
    public static function runOders($PluginOrders)
    {
        global $data;
        $PluginController = null;
        foreach ($PluginOrders AS $order) {
//            global $stime;
//            global $Robot;
//            $etime = microtime(true);
//            $Robot->sendPrivateMsg(1353693508,MsgTool::deCodeHtml($etime - $stime),false);
            $pro = explode($order['order_name'], $data['message']);
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
    */
}