<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../Application/');
// 加载框架引导文件
require __DIR__ . '/../CoolQPHP/start.php';


$Q = new CoolQSDK\CoolQSDK('127.0.0.1',5700,'slight');
echo  $Q->sendPrivateMsg(1353693508,'test');
