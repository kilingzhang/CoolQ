<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/22
 * Time: 14:26
 */

include 'Config.php';

date_default_timezone_set("Asia/Shanghai");
header("Content-type: charset=utf-8");

define('COOLQ_VERSION', '0.1.0');
define('COOLQ_START_TIME', microtime(true));
define('COOLQ_START_MEM', memory_get_usage());
define('EXT', '.php');
define('DS', DIRECTORY_SEPARATOR);
defined('COOLQ_PATH') or define('COOLQ_PATH', __DIR__ . DS);
define('LIB_PATH', COOLQ_PATH . 'Library' . DS);
defined('ROOT_PATH') or define('ROOT_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . DS);
defined('VENDOR_PATH') or define('VENDOR_PATH', ROOT_PATH . 'vendor' . DS);
defined('PLUGIN_PATH') or define('PLUGIN_PATH', ROOT_PATH . 'Plugin' . DS);


defined('INFO') or define('INFO', 1); // 环境变量的配置前缀
defined('WARN') or define('WARN', 2); // 环境变量的配置前缀
defined('ERROR') or define('ERROR', 3); // 环境变量的配置前缀
defined('DEBUG') or define('DEBUG',4); // 环境变量的配置前缀


defined('ON') or define('ON',0); // 开启
defined('OFF') or define('OFF',-1); // 关闭
defined('FOLLOW') or define('FOLLOW',1); // 跟随
defined('REPLYAT') or define('REPLYAT',2); // 跟随
defined('REPLYATFOLLOW') or define('REPLYATFOLLOW',3); // 跟随AT


// 环境常量
define('IS_CLI', PHP_SAPI == 'cli' ? true : true);
define('IS_WIN', strpos(PHP_OS, 'WIN') !== false);
define('NOW_TIME',      $_SERVER['REQUEST_TIME']);
define('REQUEST_METHOD',$_SERVER['REQUEST_METHOD']);
define('IS_GET',        REQUEST_METHOD =='GET' ? true : false);
define('IS_POST',       REQUEST_METHOD =='POST' ? true : false);
define('IS_PUT',        REQUEST_METHOD =='PUT' ? true : false);
define('IS_DELETE',     REQUEST_METHOD =='DELETE' ? true : false);

// 载入Loader类
require LIB_PATH . 'Loader.php';
// 注册自动加载
\Library\Loader::register();

\Library\Log::init(\Library\Config::getDbConfig());

