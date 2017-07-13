<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/2/14
 * Time: 13:37
 */
namespace CoolQ\Plugin;
use CoolQ\Robot\Robot;
use CoolQ\MsgTool;
use CoolQSDK\CQ;

class Plugin
{


    public $Intercept;
    public static $Plugin = null;

    public function __construct()
    {

        $this->Intercept = false;

    }

    /**
     * @param bool $bool
     */
    public function setIntercept($bool = true)
    {
        $this->Intercept = $bool;
    }

    /**
     * @return bool
     */
    public function isIntercept()
    {
        return $this->Intercept;
    }


    public function isManager($QQ)
    {

    }

    /**
     * @return mixed
     */
    public function getGetData()
    {
        return $this->getData;
    }

    /**
     * @return mixed
     */
    public function getRobot()
    {
        return $this->Robot;
    }

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

}