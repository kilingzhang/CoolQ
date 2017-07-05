<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/5 0005
 * Time: 14:39
 */

namespace CoolQ\Robot;

use CoolQSDK\CoolQSDK;
use CoolQ\Db\RobotSql;
use CoolQ\Config;

class Robot
{

    protected static $instance = null;
    private $path;
    private $CoolQ;
    private $CreateTime;
    private $status;
    private $manager;
    private $group_white_list;
    private $group_black_list;
    private $qq_white_list;
    private $qq_black_list;
    private $keyword;
    private $is_group_white_list;
    private $is_group_black_list;
    private $is_qq_white_list;
    private $is_qq_black_list;
    private $is_at;
    private $is_keyword;
    private $is_follow;
    private $is_on_friend;
    private $is_on_group;
    private $is_on_discuss;
    private $is_agree_friend;
    private $is_agree_group;
    private $is_on_plugin;

    /**
     * Robot constructor.
     * @param $HOST
     * @param $PORT
     * @param $TOKEN
     */
    private function __construct($HOST, $PORT, $TOKEN)
    {
        self::$instance = RobotSql::getInstance(Config::getDbConfig());
        $this->host = $HOST;
        $this->port = $PORT;
        $this->token = $TOKEN;
        $this->path = $this->host  . ':' . $this->port;
        $this->CoolQ = new CoolQSDK($HOST, $PORT, $TOKEN);
        $this->QQ = Config::getQQ();
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    /**
     * 单例模式获取Robot对象
     * @param $HOST
     * @param $PORT
     * @param $TOKEN
     * @return \CoolQ\Db\MySql|RobotSql|Robot|null
     */
    public static function getInstance($HOST, $PORT, $TOKEN)
    {
        // 检查对象是否已经存在，不存在则实例化后保存到$instance属性
        if (self::$instance == null) {
            self::$instance = new self($HOST, $PORT, $TOKEN);
        }
        return self::$instance;
    }

    /**
     * @param $plugin_class_name
     * @return null
     */
    public static function runPlugin($plugin_class_name, $getData, $Robot)
    {
        $Plugin = null;
//        echo $plugin_class_name . "<br>";
        include_once "Plugin/$plugin_class_name/" . $plugin_class_name . ".php";
        eval("@\$Plugin = new " . $plugin_class_name . "(\$getData,\$Robot);");
        if ($Plugin == null) return null;
        @$Plugin->Start();
        return @$Plugin;
    }


    public function getRobotPluginOrders()
    {
        $sql = "SELECT coolq_plugin_list.name,coolq_plugin_orders.plugin_id,coolq_plugin_orders.order_name,coolq_plugin_list.priority,coolq_plugin_list.status,coolq_plugin_list.plugin_class FROM coolq_plugin_orders,coolq_plugin_list WHERE  coolq_plugin_orders.	plugin_id = coolq_plugin_list.id   ORDER BY  coolq_plugin_list.priority ASC";
        $rs = $this->DB->query($sql);
        $array = array();
        while ($r = $this->DB->getone($rs)) {
            $array[] = $r;
        }
        return $array;
    }

    /**
     * 获取CoolQ对象
     * @return CoolQSDK
     */
    public function getRobotCoolQ(){
        return $this->CoolQ;
    }

    /**
     * 获取运行机器人QQ
     * @return int
     */
    public function getQQ()
    {
        return $this->QQ;
    }



    /**
     * @return mixed
     */
    public function getRobotHost()
    {
        return $this->host;
    }



    /**
     * @return mixed
     */
    public function getRobotPort()
    {
        return $this->port;
    }


    /**
     * @return mixed
     */
    public function getRobotToken()
    {
        return $this->token;
    }



    /**
     * @return string
     */
    public function getRobotPath()
    {
        return $this->path;
    }


    /**
     * @return mixed
     */
    public function getRobotCreateTime()
    {
        $this->CreateTime = self::$instance->getCreateTime($this->QQ);
        return $this->CreateTime;
    }


    /**
     * @return mixed
     */
    public function getRobotStatus()
    {
        $this->status = self::$instance->getStatus($this->QQ);
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setRobotStatus($status)
    {
        self::$instance->setStatus($this->QQ,$status);
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getRobotManager()
    {
        $this->manager = self::$instance->getManage($this->QQ);
        return $this->manager;
    }

    /**
     * @param mixed $manager
     */
    public function setRobotManager($manager)
    {
        self::$instance->setManager($this->QQ,$manager);
        $this->manager = $manager;
    }

    /**
     * @return mixed
     */
    public function getRobotGroupWhiteList()
    {
        $this->group_white_list = self::$instance->getGroupWhiteList($this->QQ);
        return $this->group_white_list;
    }

    /**
     * @param mixed $group_white_list
     */
    public function setRobotGroupWhiteList($group_white_list)
    {
        self::$instance->setGroupWhiteList($this->QQ,$group_white_list);
        $this->group_white_list = $group_white_list;
    }

    /**
     * @return mixed
     */
    public function getRobotGroupBlackList()
    {
        $this->group_black_list = self::$instance->getGroupBlackList($this->QQ);
        return $this->group_black_list;
    }

    /**
     * @param mixed $group_black_list
     */
    public function setRobotGroupBlackList($group_black_list)
    {
        self::$instance->setGroupBlackList($this->QQ,$group_black_list);
        $this->group_black_list = $group_black_list;
    }

    /**
     * @return mixed
     */
    public function getRobotQqWhiteList()
    {
        $this->qq_white_list = self::$instance->getQqWhiteList($this->QQ);
        return $this->qq_white_list;
    }

    /**
     * @param mixed $qq_white_list
     */
    public function setRobotQqWhiteList($qq_white_list)
    {
        self::$instance->setQqWhiteList($this->QQ,$qq_white_list);
        $this->qq_white_list = $qq_white_list;
    }

    /**
     * @return mixed
     */
    public function getRobotQqBlackList()
    {
        $this->qq_black_list = self::$instance->getQqBlackList($this->QQ);
        return $this->qq_black_list;
    }

    /**
     * @param mixed $qq_black_list
     */
    public function setRobotQqBlackList($qq_black_list)
    {
        self::$instance->setQqBlackList($this->QQ,$qq_black_list);
        $this->qq_black_list = $qq_black_list;
    }

    /**
     * @return mixed
     */
    public function getRobotKeyword()
    {
        $this->keyword = self::$instance->getKeyword($this->QQ);
        return $this->keyword;
    }

    /**
     * @param mixed $keyword
     */
    public function setRobotKeyword($keyword)
    {
        self::$instance->setKeyword($this->QQ,$keyword);
        $this->keyword = $keyword;
    }

    /**
     * @return mixed
     */
    public function getRobotIsGroupWhiteList()
    {
        $this->is_group_white_list = self::$instance->getIsGroupWhiteList($this->QQ);
        return $this->is_group_white_list;
    }

    /**
     * @param mixed $is_group_white_list
     */
    public function setRobotIsGroupWhiteList($is_group_white_list)
    {
        self::$instance->setIsGroupWhiteList($this->QQ,$is_group_white_list);
        $this->is_group_white_list = $is_group_white_list;
    }

    /**
     * @return mixed
     */
    public function getRobotIsGroupBlackList()
    {
        $this->is_group_black_list = self::$instance->getIsGroupBlackList($this->QQ);
        return $this->is_group_black_list;
    }

    /**
     * @param mixed $is_group_black_list
     */
    public function setRobotIsGroupBlackList($is_group_black_list)
    {
        self::$instance->setIsGroupBlackList($this->QQ,$is_group_black_list);
        $this->is_group_black_list = $is_group_black_list;
    }

    /**
     * @return mixed
     */
    public function getRobotIsQqWhiteList()
    {
        $this->is_qq_white_list = self::$instance->getIsQqWhiteList($this->QQ);
        return $this->is_qq_white_list;
    }

    /**
     * @param mixed $is_qq_white_list
     */
    public function setRobotIsQqWhiteList($is_qq_white_list)
    {
        self::$instance->setIsQqWhiteList($this->QQ,$is_qq_white_list);
        $this->is_qq_white_list = $is_qq_white_list;
    }

    /**
     * @return mixed
     */
    public function getRobotIsQqBlackList()
    {
        $this->is_qq_black_list = self::$instance->getIsQqBlackList($this->QQ);
        return $this->is_qq_black_list;
    }

    /**
     * @param mixed $is_qq_black_list
     */
    public function setRobotIsQqBlackList($is_qq_black_list)
    {
        self::$instance->setIsQqBlackList($this->QQ,$is_qq_black_list);
        $this->is_qq_black_list = $is_qq_black_list;
    }

    /**
     * @return mixed
     */
    public function getRobotIsAt()
    {
        $this->is_at = self::$instance->getIsAt($this->QQ);
        return $this->is_at;
    }

    /**
     * @param mixed $is_at
     */
    public function setRobotIsAt($is_at)
    {
        self::$instance->setIsAt($this->QQ,$is_at);
        $this->is_at = $is_at;
    }

    /**
     * @return mixed
     */
    public function getRobotIsKeyword()
    {
        $this->is_keyword = self::$instance->getIsKeyWord($this->QQ);
        return $this->is_keyword;
    }

    /**
     * @param mixed $is_keyword
     */
    public function setRobotIsKeyword($is_keyword)
    {
        self::$instance->setIsKeyword($this->QQ,$is_keyword);
        $this->is_keyword = $is_keyword;
    }

    /**
     * @return mixed
     */
    public function getRobotIsFollow()
    {
        $this->is_follow = self::$instance->getIsFollow($this->QQ);
        return $this->is_follow;
    }

    /**
     * @param mixed $is_follow
     */
    public function setRobotIsFollow($is_follow)
    {
        self::$instance->setIsFollow($this->QQ,$is_follow);
        $this->is_follow = $is_follow;
    }

    /**
     * @return mixed
     */
    public function getRobotIsOnFriend()
    {
        $this->is_on_friend = self::$instance->getIsOnFriend($this->QQ);
        return $this->is_on_friend;
    }

    /**
     * @param mixed $is_on_friend
     */
    public function setRobotIsOnFriend($is_on_friend)
    {
        self::$instance->setIsOnFriend($this->QQ,$is_on_friend);
        $this->is_on_friend = $is_on_friend;
    }

    /**
     * @return mixed
     */
    public function getRobotIsOnGroup()
    {
        $this->is_on_group = self::$instance->getIsOnGroup($this->QQ);
        return $this->is_on_group;
    }

    /**
     * @param mixed $is_on_group
     */
    public function setRobotIsOnGroup($is_on_group)
    {
        self::$instance->setIsOnGroup($this->QQ,$is_on_group);
        $this->is_on_group = $is_on_group;
    }

    /**
     * @return mixed
     */
    public function getRobotIsOnDiscuss()
    {
        $this->is_on_discuss = self::$instance->getIsOnDiscuss($this->QQ);
        return $this->is_on_discuss;
    }

    /**
     * @param mixed $is_on_discuss
     */
    public function setRobotIsOnDiscuss($is_on_discuss)
    {
        self::$instance->setIsOnDiscuss($this->QQ,$is_on_discuss);
        $this->is_on_discuss = $is_on_discuss;
    }

    /**
     * @return mixed
     */
    public function getRobotIsAgreeFriend()
    {
        $this->is_agree_friend = self::$instance->getIsOnAgreeFriend($this->QQ);
        return $this->is_agree_friend;
    }

    /**
     * @param mixed $is_agree_friend
     */
    public function setRobotIsAgreeFriend($is_agree_friend)
    {
        self::$instance->setIsOnAgreeFriend($this->QQ,$is_agree_friend);
        $this->is_agree_friend = $is_agree_friend;
    }

    /**
     * @return mixed
     */
    public function getRobotIsAgreeGroup()
    {
        $this->is_agree_group = self::$instance->getIsOnAgreeGroup($this->QQ);
        return $this->is_agree_group;
    }

    /**
     * @param mixed $is_agree_group
     */
    public function setRobotIsAgreeGroup($is_agree_group)
    {
        self::$instance->setIsOnAgreeGroup($this->QQ,$is_agree_group);
        $this->is_agree_group = $is_agree_group;
    }

    /**
     * @return mixed
     */
    public function getRobotIsOnPlugin()
    {
        $this->is_on_plugin = self::$instance->getIsOnPlugin($this->QQ);
        return $this->is_on_plugin;
    }

    /**
     * @param mixed $is_on_plugin
     */
    public function setRobotIsOnPlugin($is_on_plugin)
    {
        self::$instance->setIsOnPlugin($this->QQ,$is_on_plugin);
        $this->is_on_plugin = $is_on_plugin;
    }







}