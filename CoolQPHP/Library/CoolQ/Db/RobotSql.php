<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/5
 * Time: 0:32
 */

namespace CoolQ\Db;


use CoolQ\Config;
use CoolQ\Log;

class RobotSql extends MySql
{
    protected $dbHost;
    protected $dbUser;
    protected $dbPassword;
    protected $dbTable;
    protected $dbPort;
    protected $link;
    protected $result;
    protected static $instance = null;

    protected function __construct($dbHost, $dbUser, $dbPassword, $dbTable, $dbPort)
    {
        $this->dbHost = $dbHost;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->dbTable = $dbTable;
        $this->dbPort = $dbPort;
        self::connect($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbTable, $this->dbPort);

    }

    protected function __clone()
    {
    }

    protected function __wakeup()
    {
    }

    public static function getInstance($config = array())
    {
        // 检查对象是否已经存在，不存在则实例化后保存到$instance属性
        if (self::$instance == null) {
            self::$instance = new self($config['dbHost'], $config['dbUser'], $config['dbPassword'], $config['dbTable'], $config['dbPort']);
        }
        return self::$instance;
    }

    /**
     *  获取机器人的配置信息
     * @param $QQ
     * @return mixed
     */
    public  function getRobot($QQ){
        $sql = "SELECT * FROM coolq_robot WHERE qq = '$QQ' ";
        $res = self::$instance->query($sql);
        if (mysqli_num_rows($res) == 1) {
            return self::$instance->getOne($res);
        } else if (mysqli_num_rows($res) == 0) {
            $sql = "insert into coolq_robot(qq,time,manager,group_white_list, group_black_list, qq_white_list, qq_black_list,keyword) values ('$QQ','" . date('Y-m-d H:i:s', time()) . "','[" . Config::getManager() . "]','[]','[]','[]','[]','[]') ";
            $res = self::$instance->query($sql);
            if ($res) {
                Log::Info("初始化机器人成功，机器人：{$QQ}前来报道~");
            } else {
                Log::Warn("初始化机器人失败，机器人：{$QQ}未添加成功~");
            }
            $sql = "SELECT * FROM coolq_robot WHERE qq = '$QQ'";
            $res = self::$instance->query($sql);
            return self::$instance->getOne($res);
        } else {
            Log::Warn("初始化机器人失败，机器人：{$QQ}查询失败~　发生意外的事情~ 请联系管理员查看数据库排查问题￣");
        }
    }

    /**
     * 获取机器人状态　
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getStatus($QQ)
    {
        $sql = "SELECT status FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if(!$res){
            Log::Warn("获取机器人开启状态失败",get_class(),'SQL');
            return $res;
        }
        $status = self::$instance->getOne($res)['status'];
        Log::Info("获取机器人开启状态：$status",get_class(),'SQL');
        return $status;
    }

    /**
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setStatus($QQ,$status)
    {
        $status == true ? $status = 1 : $status = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET status = '$status' WHERE qq = '$QQ'");
        if($res){
            Log::Info("获取机器人状态设置成功：$status",get_class(),'SQL');
        }else{
            Log::Warn("获取机器人状态设置失败",get_class(),'SQL');
        }
        return $res;
    }

    /**
     * @param $QQ
     * @return array|mixed
     */
    public function getManage($QQ)
    {
        $sql = "SELECT manager FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if(!$res){
            Log::Warn("获取机器人管理员失败",get_class(),'SQL');
            return $res;
        }
        $manager = self::$instance->getOne($res)['manager'];
        Log::Info("获取机器人管理员：$manager",get_class(),'SQL');
        return isset($manager) && $manager != "" ? json_decode($manager,true) : array();
    }

    /**
     * @param $QQ
     * @param $manager
     * @return bool
     */
    public function setManager($QQ,$manager)
    {
        if(!is_array($manager)){
            Log::Warn("设置机器人管理员失败：传入的参数非数组形式：$manager",get_class(),'SQL');
            return false;
        }
        $manager = json_encode($manager, JSON_UNESCAPED_UNICODE);
        $res = self::$instance->query("UPDATE coolq_robot SET manager = '$manager' WHERE qq = '$QQ'");
        if($res){
            Log::Info("设置机器人管理员成功：". json_encode($manager,JSON_UNESCAPED_UNICODE),get_class(),'SQL');
        }else{
            Log::Warn("设置机器人管理员失败",get_class(),'SQL');
        }
        return $res;
    }

    /**
     * 获取群组白名单
     * 如果白名单和黑名单同时开启　以白名单为优先级高的顺序执行
     * @param $QQ
     * @return array|mixed
     */
    public function getGroupWhiteList($QQ)
    {
        $sql = "SELECT group_white_list FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if(!$res){
            Log::Warn("获取机器人群组白名单失败",get_class(),'SQL');
            return $res;
        }
        $group_white_list = self::$instance->getOne($res)['group_white_list'];
        Log::Info("获取机器人白名单：$group_white_list",get_class(),'SQL');
        return isset($group_white_list) && $group_white_list != "" ? json_decode($group_white_list,true) : array();
    }



    /**
     * 设置群组白名单
     * 如果白名单和黑名单同时开启　以白名单为优先级高的顺序执行
     * @param $QQ
     * @param $group_white_list
     */
    public function setGroupWhiteList($QQ,$group_white_list)
    {
        if(!is_array($group_white_list)){
            Log::Warn("设置机器人群组白名单失败：传入的参数非数组形式：$group_white_list",get_class(),'SQL');
            return false;
        }
        $group_white_list = json_encode($group_white_list, JSON_UNESCAPED_UNICODE);
        $res = self::$instance->query("UPDATE coolq_robot SET group_white_list = '$group_white_list' WHERE qq = '$this->QQ'");
        if($res){
            Log::Info("设置机器人群组白名单成功：". json_encode($group_white_list,JSON_UNESCAPED_UNICODE),get_class(),'SQL');
        }else{
            Log::Warn("设置机器人群组白名单失败",get_class(),'SQL');
        }
        return $res;
    }


    //todo

    #明天起来再搞把￣￣￣

    /**
     * @param mixed $group_black_list
     */
    public function setGroupBlackList($group_black_list)
    {
        $this->group_black_list = $group_black_list;
        $group_black_list = json_encode($group_black_list, JSON_UNESCAPED_UNICODE);
        self::$instance->query("UPDATE coolq_robot SET group_black_list = '$group_black_list' WHERE qq = '$this->QQ'");

    }

    /**
     * @param mixed $qq_white_list
     */
    public function setQqWhiteList($qq_white_list)
    {
        $this->qq_white_list = $qq_white_list;
        $qq_white_list = json_encode($qq_white_list, JSON_UNESCAPED_UNICODE);
        self::$instance->query("UPDATE coolq_robot SET qq_white_list = '$qq_white_list' WHERE qq = '$this->QQ'");

    }

    /**
     * @param mixed $qq_black_list
     */
    public function setQqBlackList($qq_black_list)
    {
        $this->qq_black_list = $qq_black_list;
        $qq_black_list = json_encode($qq_black_list, JSON_UNESCAPED_UNICODE);
        self::$instance->query("UPDATE coolq_robot SET qq_black_list = '$qq_black_list' WHERE qq = '$this->QQ'");

    }

    /**
     * @param mixed $is_qq_white_list
     */
    public function setIsQqWhiteList($is_qq_white_list)
    {
        if ($is_qq_white_list) {
            $is_qq_white_list = 1;
        } else {
            $is_qq_white_list = 0;
        }
        $this->is_qq_white_list = $is_qq_white_list;
        self::$instance->query("UPDATE coolq_robot SET is_qq_white_list = '$is_qq_white_list' WHERE qq = '$this->QQ'");

    }

    /**
     * @param mixed $is_qq_black_list
     */
    public function setIsQqBlackList($is_qq_black_list)
    {
        if ($is_qq_black_list) {
            $is_qq_black_list = 1;
        } else {
            $is_qq_black_list = 0;
        }
        $this->is_qq_black_list = $is_qq_black_list;
        self::$instance->query("UPDATE coolq_robot SET is_qq_black_list = '$is_qq_black_list' WHERE qq = '$this->QQ'");

    }

    /**
     * @param mixed $is_group_white_list
     */
    public function setIsGroupWhiteList($is_group_white_list)
    {
        if ($is_group_white_list) {
            $is_group_white_list = 1;
        } else {
            $is_group_white_list = 0;
        }
        $this->is_group_white_list = $is_group_white_list;
        self::$instance->query("UPDATE coolq_robot SET is_group_white_list = '$is_group_white_list' WHERE qq = '$this->QQ'");

    }

    /**
     * @param mixed $is_group_black_list
     */
    public function setIsGroupBlackList($is_group_black_list)
    {
        if ($is_group_black_list) {
            $is_group_black_list = 1;
        } else {
            $is_group_black_list = 0;
        }
        $this->is_group_black_list = $is_group_black_list;
        self::$instance->query("UPDATE coolq_robot SET is_group_black_list = '$is_group_black_list' WHERE qq = '$this->QQ'");

    }

    /**
     * @param mixed $is_at
     */
    public function setIsAt($is_at)
    {
        if ($is_at) {
            $is_at = 1;
        } else {
            $is_at = 0;
        }
        $this->is_at = $is_at;
        self::$instance->query("UPDATE coolq_robot SET is_at = '$is_at' WHERE qq = '$this->QQ'");

    }

    /**
     * @param mixed $is_keyword
     */
    public function setIsKeyword($is_keyword)
    {
        if ($is_keyword) {
            $is_keyword = 1;
        } else {
            $is_keyword = 0;
        }
        $this->is_keyword = $is_keyword;
        self::$instance->query("UPDATE coolq_robot SET is_keyword = '$is_keyword' WHERE qq = '$this->QQ'");

    }

    /**
     * @param mixed $is_on_friend
     */
    public function setIsOnFriend($is_on_friend)
    {
        if ($is_on_friend) {
            $is_on_friend = 1;
        } else {
            $is_on_friend = 0;
        }
        $this->is_on_friend = $is_on_friend;
        self::$instance->query("UPDATE coolq_robot SET is_on_friend = '$is_on_friend' WHERE qq = '$this->QQ'");
    }

    /**
     * @param mixed $is_on_group
     */
    public function setIsOnGroup($is_on_group)
    {
        if ($is_on_group) {
            $is_on_group = 1;
        } else {
            $is_on_group = 0;
        }
        $this->is_on_group = $is_on_group;
        self::$instance->query("UPDATE coolq_robot SET is_on_group = 'is_on_group' WHERE qq = '$this->QQ'");
    }

    /**
     * @param mixed $is_on_discuss
     */
    public function setIsOnDiscuss($is_on_discuss)
    {
        if ($is_on_discuss) {
            $is_on_discuss = 1;
        } else {
            $is_on_discuss = 0;
        }
        $this->is_on_discuss = $is_on_discuss;
        self::$instance->query("UPDATE coolq_robot SET is_on_discuss = '$is_on_discuss' WHERE qq = '$this->QQ'");
    }


    /**
     * @param mixed $keyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
        $keyword = json_encode($keyword, JSON_UNESCAPED_UNICODE);
        self::$instance->query("UPDATE coolq_robot SET keyword = '$keyword' WHERE qq = '$this->QQ'");

    }

    /**
     * @param mixed $is_agree_friend
     */
    public function setIsAgreeFriend($is_agree_friend)
    {
        if ($is_agree_friend) {
            $is_agree_friend = 1;
        } else {
            $is_agree_friend = 0;
        }
        $this->is_agree_friend = $is_agree_friend;
        self::$instance->query("UPDATE coolq_robot SET is_agree_friend = '$is_agree_friend' WHERE qq = '$this->QQ'");

    }

    /**
     * @param mixed $is_agree_group
     */
    public function setIsAgreeGroup($is_agree_group)
    {
        if ($is_agree_group) {
            $is_agree_group = 1;
        } else {
            $is_agree_group = 0;
        }
        $this->is_agree_group = $is_agree_group;
        self::$instance->query("UPDATE coolq_robot SET is_agree_group = '$is_agree_group' WHERE qq = '$this->QQ'");

    }



    /**
     * @return MySql|null
     */
    public function getDB()
    {
        return $this->DB;
    }

    /**
     * @param MySql|null $DB
     */
    public function setDB($DB)
    {
        $this->DB = $DB;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return CoolQSDK
     */
    public function getCoolQ()
    {
        return $this->CoolQ;
    }

    /**
     * @param CoolQSDK $CoolQ
     */
    public function setCoolQ($CoolQ)
    {
        $this->CoolQ = $CoolQ;
    }

    /**
     * @return mixed
     */
    public function getQQ()
    {
        return $this->QQ;
    }

    /**
     * @param mixed $QQ
     */
    public function setQQ($QQ)
    {
        $this->QQ = $QQ;
    }

    /**
     * @return mixed
     */
    public function getisFollow()
    {
        return $this->is_follow;
    }

    /**
     * @param mixed $is_follow
     */
    public function setIsFollow($is_follow)
    {
        $this->is_follow = $is_follow;
    }






}