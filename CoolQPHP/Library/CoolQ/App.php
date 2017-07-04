<?php

/**
 * Created by PhpStorm.
 * User: Kilingzhang
 * Date: 2017/7/1
 * Time: 11:41
 */

namespace CoolQ;


use CoolQ\exception\HttpException;
use CoolQ\exception\HttpResponseException;
use CoolQ\exception\RouteNotFoundException;
use CoolQ\Request;
use CoolQ\Debug;
use CoolQSDK\CoolQSDK;

class App
{
    /**
     * @var bool 是否初始化过
     */
    protected static $init = false;

    /**
     * @var string 当前模块路径
     */
    public static $modulePath;

    /**
     * @var bool 应用调试模式
     */
    public static $debug = true;

    /**
     * @var string 应用类库命名空间
     */
    public static $namespace = 'app';

    /**
     * @var bool 应用类库后缀
     */
    public static $suffix = false;

    /**
     * @var bool 应用路由检测
     */
    protected static $routeCheck;

    /**
     * @var bool 严格路由检测
     */
    protected static $routeMust;

    protected static $dispatch;
    protected static $file = [];

    function __construct()
    {

    }

    public static function run()
    {

        //初始化配置
        Config::init();

        //初始化日志
        Log::init();


        // 应用初始化标签
        Hook::do_action('app_init');
        App::init();
        // 应用开始标签
        Hook::do_action('app_begin');
        App::start();
        // 应用结束标签
        Hook::do_action('app_end');
        return;


    }

    private static function init()
    {
    }

    private static function start()
    {
        // 当前访问
        defined('PATH_INFO') or define('PATH_INFO', trim(\CoolQ\Request::server('PATH_INFO', null)['PATH_INFO'], '/'));
        $Route = explode('/', PATH_INFO);
        //http://server/module/controller/action/param/value/…
        global $_Param;
        global $_Module;
        global $_Controller;
        global $_Action;
        $_Param = null;
        if (count($Route) > 0 && $Route[0] != ''){
            $_Module = isset($Route[0]) ? $Route[0] : "";
            $_Controller = isset($Route[1]) ? $Route[1] : "";
            $_Action = isset($Route[2]) ? $Route[2] : "";
            if(count($Route) > 3){
                for($i=3 ; $i<count($Route) ; $i+=2){
                    $_Param[$Route[$i]] = isset($Route[$i+1]) ? htmlspecialchars($Route[$i+1]) : "";
                }
            }
        }else{
            //上报事件
            require ROOT_PATH . 'App\Api\index.php';
        }



    }



}