<?php
namespace Library;

/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/22
 * Time: 14:22
 */
class Loader
{
    // 类名映射
    protected static $map = [];
    // 命名空间别名
    protected static $namespaceAlias = [];
    // PSR-4
    private static $prefixLengthsPsr4 = [];
    private static $prefixDirsPsr4    = [];
    private static $fallbackDirsPsr4  = [];
    // PSR-0
    private static $prefixesPsr0     = [];
    private static $fallbackDirsPsr0 = [];
    // 自动加载的文件
    private static $autoloadFiles = [];

    // 自动加载
    public static function autoload($class)
    {
        // 检测命名空间别名
        if (!empty(self::$namespaceAlias)) {
            $namespace = dirname($class);
            if (isset(self::$namespaceAlias[$namespace])) {
                $original = self::$namespaceAlias[$namespace] . '\\' . basename($class);
                if (class_exists($original)) {
                    return class_alias($original, $class, false);
                }
            }
        }
        if ($file = self::findFile($class)) {
            // Win环境严格区分大小写
            if (IS_WIN && pathinfo($file, PATHINFO_FILENAME) != pathinfo(realpath($file), PATHINFO_FILENAME)) {
                return false;
            }
            __include_file($file);
            return true;
        }
    }
    /**
     * 查找文件
     * @param $class
     * @return bool
     */
    private static function findFile($class)
    {
        if (!empty(self::$map[$class])) {
            // 类库映射
            return self::$map[$class];
        }
        // 查找 PSR-4
        $logicalPathPsr4 = strtr($class, '\\', DS) . EXT;
        $first = $class[0];
        if (isset(self::$prefixLengthsPsr4[$first])) {
            foreach (self::$prefixLengthsPsr4[$first] as $prefix => $length) {
                if (0 === strpos($class, $prefix)) {
                    foreach (self::$prefixDirsPsr4[$prefix] as $dir) {
                        if (is_file($file = $dir . DS . substr($logicalPathPsr4, $length))) {
                            return $file;
                        }
                    }
                }
            }
        }
        // 查找 PSR-4 fallback dirs
        foreach (self::$fallbackDirsPsr4 as $dir) {
            if (is_file($file = $dir . DS . $logicalPathPsr4)) {
                return $file;
            }
        }
        // 查找 PSR-0
        if (false !== $pos = strrpos($class, '\\')) {
            // namespaced class name
            $logicalPathPsr0 = substr($logicalPathPsr4, 0, $pos + 1)
                . strtr(substr($logicalPathPsr4, $pos + 1), '_', DS);
        } else {
            // PEAR-like class name
            $logicalPathPsr0 = strtr($class, '_', DS) . EXT;
        }
        if (isset(self::$prefixesPsr0[$first])) {
            foreach (self::$prefixesPsr0[$first] as $prefix => $dirs) {
                if (0 === strpos($class, $prefix)) {
                    foreach ($dirs as $dir) {
                        if (is_file($file = $dir . DS . $logicalPathPsr0)) {
                            return $file;
                        }
                    }
                }
            }
        }
        // 查找 PSR-0 fallback dirs
        foreach (self::$fallbackDirsPsr0 as $dir) {
            if (is_file($file = $dir . DS . $logicalPathPsr0)) {
                return $file;
            }
        }
        return self::$map[$class] = false;
    }
    // 注册classmap
    public static function addClassMap($class, $map = '')
    {
        if (is_array($class)) {
            self::$map = array_merge(self::$map, $class);
        } else {
            self::$map[$class] = $map;
        }
    }
    // 注册命名空间
    public static function addNamespace($namespace, $path = '')
    {
        if (is_array($namespace)) {
            foreach ($namespace as $prefix => $paths) {
                self::addPsr4($prefix . '\\', rtrim($paths, DS), true);
            }
        } else {
            self::addPsr4($namespace . '\\', rtrim($path, DS), true);
        }
    }
    // 添加Ps0空间
    private static function addPsr0($prefix, $paths, $prepend = false)
    {
        if (!$prefix) {
            if ($prepend) {
                self::$fallbackDirsPsr0 = array_merge(
                    (array) $paths,
                    self::$fallbackDirsPsr0
                );
            } else {
                self::$fallbackDirsPsr0 = array_merge(
                    self::$fallbackDirsPsr0,
                    (array) $paths
                );
            }
            return;
        }
        $first = $prefix[0];
        if (!isset(self::$prefixesPsr0[$first][$prefix])) {
            self::$prefixesPsr0[$first][$prefix] = (array) $paths;
            return;
        }
        if ($prepend) {
            self::$prefixesPsr0[$first][$prefix] = array_merge(
                (array) $paths,
                self::$prefixesPsr0[$first][$prefix]
            );
        } else {
            self::$prefixesPsr0[$first][$prefix] = array_merge(
                self::$prefixesPsr0[$first][$prefix],
                (array) $paths
            );
        }
    }
    // 添加Psr4空间
    private static function addPsr4($prefix, $paths, $prepend = false)
    {
        if (!$prefix) {
            // Register directories for the root namespace.
            if ($prepend) {
                self::$fallbackDirsPsr4 = array_merge(
                    (array) $paths,
                    self::$fallbackDirsPsr4
                );
            } else {
                self::$fallbackDirsPsr4 = array_merge(
                    self::$fallbackDirsPsr4,
                    (array) $paths
                );
            }
        } elseif (!isset(self::$prefixDirsPsr4[$prefix])) {
            // Register directories for a new namespace.
            $length = strlen($prefix);
            if ('\\' !== $prefix[$length - 1]) {
                throw new \InvalidArgumentException("A non-empty PSR-4 prefix must end with a namespace separator.");
            }
            self::$prefixLengthsPsr4[$prefix[0]][$prefix] = $length;
            self::$prefixDirsPsr4[$prefix]                = (array) $paths;
        } elseif ($prepend) {
            // Prepend directories for an already registered namespace.
            self::$prefixDirsPsr4[$prefix] = array_merge(
                (array) $paths,
                self::$prefixDirsPsr4[$prefix]
            );
        } else {
            // Append directories for an already registered namespace.
            self::$prefixDirsPsr4[$prefix] = array_merge(
                self::$prefixDirsPsr4[$prefix],
                (array) $paths
            );
        }
    }
    // 注册命名空间别名
    public static function addNamespaceAlias($namespace, $original = '')
    {
        if (is_array($namespace)) {
            self::$namespaceAlias = array_merge(self::$namespaceAlias, $namespace);
        } else {
            self::$namespaceAlias[$namespace] = $original;
        }
    }
    // 注册自动加载机制
    public static function register($autoload = '')
    {
        // 注册系统自动加载
        spl_autoload_register(function($className){
            $path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
            $file =  $path.'.php';
            if(is_file($file)) require $file;
        });
        // 注册命名空间定义
        self::addNamespace([
            'Library'    => LIB_PATH,
        ]);

        // Composer自动加载支持
        if (is_dir(VENDOR_PATH . 'composer' )) {
            self::registerComposerLoader();
        }

    }
    // 注册composer自动加载
    private static function registerComposerLoader()
    {
        if (is_file(VENDOR_PATH . 'composer/autoload_namespaces.php')) {
            $map = require VENDOR_PATH . 'composer/autoload_namespaces.php';
            foreach ($map as $namespace => $path) {
                self::addPsr0($namespace, $path);
            }
        }
        if (is_file(VENDOR_PATH . 'composer/autoload_psr4.php')) {
            $map = require VENDOR_PATH . 'composer/autoload_psr4.php';
            foreach ($map as $namespace => $path) {
                self::addPsr4($namespace, $path);
            }
        }
        if (is_file(VENDOR_PATH . 'composer/autoload_classmap.php')) {
            $classMap = require VENDOR_PATH . 'composer/autoload_classmap.php';
            if ($classMap) {
                self::addClassMap($classMap);
            }
        }
        if (is_file(VENDOR_PATH . 'autoload.php')) {
            $includeFiles = require VENDOR_PATH . 'autoload.php';
            foreach ($includeFiles as $fileIdentifier => $file) {
                if (empty(self::$autoloadFiles[$fileIdentifier])) {
                    __require_file($file);
                    self::$autoloadFiles[$fileIdentifier] = true;
                }
            }
        }
    }

}
/**
 * 作用范围隔离
 *
 * @param $file
 * @return mixed
 */
function __include_file($file)
{
    return include $file;
}
function __require_file($file)
{
    return require $file;
}