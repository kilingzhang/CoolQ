<?php

/**
 * Created by PhpStorm.
 * User: Kilingzhang
 * Date: 2017/7/1
 * Time: 1:07
 */
/**
 * 视图基类
 */
class View
{
    protected $variables = array();
    protected $_controller;
    protected $_action;

    function __construct($controller, $action)
    {
        $this->_controller = strtolower($controller);
        $this->_action = strtolower($action);
    }

    // 分配变量
    public function assign($name, $value)
    {
        $this->variables[$name] = $value;
    }

    // 渲染显示
    public function render()
    {
        extract($this->variables);
        $defaultHeader = APP_PATH . 'Application/Views/header.php';
        $defaultFooter = APP_PATH . 'Application/Views/footer.php';

        $controllerHeader = APP_PATH . 'Application/Views/' . $this->_controller . '/header.php';
        $controllerFooter = APP_PATH . 'Application/Views/' . $this->_controller . '/footer.php';
        $controllerLayout = APP_PATH . 'Application/Views/' . $this->_controller . '/' . $this->_action . '.php';

        // 页头文件
        if (file_exists($controllerHeader)) {
            include ($controllerHeader);
        } else {
            include ($defaultHeader);
        }

        include ($controllerLayout);

        // 页脚文件
        if (file_exists($controllerFooter)) {
            include ($controllerFooter);
        } else {
            include ($defaultFooter);
        }
    }
}