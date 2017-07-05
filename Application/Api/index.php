<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/4
 * Time: 22:51
 */
use  CoolQSDK\CoolQSDK;
$data = \CoolQ\Request::put();
$post_type = $data['post_type'];
$message_type = $data['message_type'];
$user_id = isset($data['user_id']) && array_key_exists('user_id',$data) ? $data['user_id'] : null;
$group_id = isset($data['group_id']) && array_key_exists('group_id',$data) ? $data['group_id'] : null;
$discuss_id = isset($data['discuss_id']) && array_key_exists('discuss_id',$data) ? $data['discuss_id'] : null;
$message = $data['message'];
$Robot = \CoolQ\Robot\Robot::getInstance('127.0.0.1',5700,'slight');

$Robot->getRobotQqWhiteList();




