<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang
 * Date: 2017/7/1
 * Time: 8:54
 */

date_default_timezone_set("Asia/Shanghai");
define('COOLQ_VERSION', '0.2.0');
define('COOLQ_START_TIME', microtime(true));
define('COOLQ_START_MEM', memory_get_usage());
define('EXT', '.php');
define('DS', DIRECTORY_SEPARATOR);
defined('COOLQ_PATH') or define('COOLQ_PATH', __DIR__ . DS);
define('LIB_PATH', COOLQ_PATH . 'Library' . DS);
define('CORE_PATH', LIB_PATH . 'COOLQ' . DS);
define('TRAIT_PATH', LIB_PATH . 'traits' . DS);
defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . DS);
defined('ROOT_PATH') or define('ROOT_PATH', dirname(realpath(APP_PATH)) . DS);
defined('EXTEND_PATH') or define('EXTEND_PATH', ROOT_PATH . 'extend' . DS);
defined('VENDOR_PATH') or define('VENDOR_PATH', ROOT_PATH . 'vendor' . DS);
defined('RUNTIME_PATH') or define('RUNTIME_PATH', ROOT_PATH . 'Runtime' . DS);
defined('LOG_PATH') or define('LOG_PATH', RUNTIME_PATH . 'Log' . DS);
defined('CACHE_PATH') or define('CACHE_PATH', RUNTIME_PATH . 'Cache' . DS);
defined('TEMP_PATH') or define('TEMP_PATH', RUNTIME_PATH . 'temp' . DS);
defined('CONF_PATH') or define('CONF_PATH', APP_PATH); // 配置文件目录
defined('CONF_EXT') or define('CONF_EXT', EXT); // 配置文件后缀
defined('ENV_PREFIX') or define('ENV_PREFIX', 'PHP_'); // 环境变量的配置前缀

defined('INFO') or define('INFO', 1); // 环境变量的配置前缀
defined('WARN') or define('WARN', 2); // 环境变量的配置前缀
defined('ERROR') or define('ERROR', 3); // 环境变量的配置前缀
defined('DEBUG') or define('DEBUG',4); // 环境变量的配置前缀


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
require CORE_PATH . 'Loader.php';


// 注册自动加载
\CoolQ\Loader::register();