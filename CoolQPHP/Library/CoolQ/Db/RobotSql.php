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
    public function getRobot($QQ)
    {
        $sql = "SELECT * FROM coolq_robot WHERE qq = '$QQ' ";
        $res = self::$instance->query($sql);
        if (mysqli_num_rows($res) == 1) {
            $res = self::$instance->getOne($res);
            Log::Warn("获取机器人配置，机器人：￣" . json_encode($res,JSON_UNESCAPED_UNICODE));
            return $res;
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
     * 获取机器人创建时间　
     * @return mixed
     */
    public function getCreateTime($QQ)
    {
        $sql = "SELECT time FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if (!$res) {
            Log::Warn("获取机器人创建时间失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $time = self::$instance->getOne($res)['time'];
        Log::Info("获取机器人创建时间：$time", Plugin::$Plugin, 'SQL');
        return $time;
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
        if (!$res) {
            Log::Warn("获取机器人开启状态失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $status = self::$instance->getOne($res)['status'];
        Log::Info("获取机器人开启状态：$status", Plugin::$Plugin, 'SQL');
        return $status;
    }

    /**
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setStatus($QQ, $status)
    {
        $status == true ? $status = 1 : $status = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET status = '$status' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("设置机器人状态设置成功：$status", Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("设置机器人状态设置失败", Plugin::$Plugin, 'SQL');
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
        if (!$res) {
            Log::Warn("获取机器人管理员失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $manager = self::$instance->getOne($res)['manager'];
        Log::Info("获取机器人管理员：$manager", Plugin::$Plugin, 'SQL');
        return isset($manager) && $manager != "" ? json_decode($manager, true) : array();
    }

    /**
     * @param $QQ
     * @param $manager
     * @return bool
     */
    public function setManager($QQ, $manager)
    {
        if (!is_array($manager)) {
            Log::Warn("设置机器人管理员失败：传入的参数非数组形式：$manager", Plugin::$Plugin, 'SQL');
            return false;
        }
        $manager = json_encode($manager, JSON_UNESCAPED_UNICODE);
        $res = self::$instance->query("UPDATE coolq_robot SET manager = '$manager' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("设置机器人管理员成功：" . json_encode($manager, JSON_UNESCAPED_UNICODE), Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("设置机器人管理员失败", Plugin::$Plugin, 'SQL');
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
        if (!$res) {
            Log::Warn("获取机器人群组白名单失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $group_white_list = self::$instance->getOne($res)['group_white_list'];
        Log::Info("获取机器人白名单：$group_white_list", Plugin::$Plugin, 'SQL');
        return isset($group_white_list) && $group_white_list != "" ? json_decode($group_white_list, true) : array();
    }


    /**
     * 设置群组白名单
     * 如果白名单和黑名单同时开启　以白名单为优先级高的顺序执行
     * @param $QQ
     * @param $group_white_list
     */
    public function setGroupWhiteList($QQ, $group_white_list)
    {
        if (!is_array($group_white_list)) {
            Log::Warn("设置机器人群组白名单失败：传入的参数非数组形式：$group_white_list", Plugin::$Plugin, 'SQL');
            return false;
        }
        $group_white_list = json_encode($group_white_list, JSON_UNESCAPED_UNICODE);
        $res = self::$instance->query("UPDATE coolq_robot SET group_white_list = '$group_white_list' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("设置机器人群组白名单成功：" . json_encode($group_white_list, JSON_UNESCAPED_UNICODE), Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("设置机器人群组白名单失败", Plugin::$Plugin, 'SQL');
        }
        return $res;
    }

    /**
     * 获取群组黑名单
     * @param $QQ
     * @return array|mixed
     */
    public function getGroupBlackList($QQ)
    {
        $sql = "SELECT group_black_list FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if (!$res) {
            Log::Warn("获取机器人群组黑名单失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $group_black_list = self::$instance->getOne($res)['group_black_list'];
        Log::Info("获取机器人群组黑名单：$group_black_list", Plugin::$Plugin, 'SQL');
        return isset($group_black_list) && $group_black_list != "" ? json_decode($group_black_list, true) : array();
    }


    /**
     * 设置群组黑名单
     * @param $QQ
     * @param $group_black_list
     * @return bool
     */
    public function setGroupBlackList($QQ, $group_black_list)
    {
        if (!is_array($group_black_list)) {
            Log::Warn("设置机器人群组黑名单失败：传入的参数非数组形式：$group_black_list", Plugin::$Plugin, 'SQL');
            return false;
        }
        $group_black_list = json_encode($group_black_list, JSON_UNESCAPED_UNICODE);
        $res = self::$instance->query("UPDATE coolq_robot SET group_black_list = '$group_black_list' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("设置机器人群组黑名单成功：" . json_encode($group_black_list, JSON_UNESCAPED_UNICODE), Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("设置机器人群组黑名单失败", Plugin::$Plugin, 'SQL');
        }
        return $res;
    }


    /**
     * 获取QQ白名单
     * 如果白名单和黑名单同时开启　以白名单为优先级高的顺序执行
     * @param $QQ
     * @return array|mixed
     */
    public function getQqWhiteList($QQ)
    {
        $sql = "SELECT qq_white_list FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if (!$res) {
            Log::Warn("获取机器人QQ白名单失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $qq_white_list = self::$instance->getOne($res)['qq_white_list'];
        Log::Info("获取机器人QQ白名单：$qq_white_list", Plugin::$Plugin, 'SQL');
        return isset($qq_white_list) && $qq_white_list != "" ? json_decode($qq_white_list, true) : array();
    }


    /**
     * 设置QQ白名单
     * 如果白名单和黑名单同时开启　以白名单为优先级高的顺序执行
     * @param $QQ
     * @param $group_white_list
     */
    public function setQqWhiteList($QQ, $qq_white_list)
    {
        if (!is_array($qq_white_list)) {
            Log::Warn("设置机器人QQ白名单失败：传入的参数非数组形式：$qq_white_list", Plugin::$Plugin, 'SQL');
            return false;
        }
        $qq_white_list = json_encode($qq_white_list, JSON_UNESCAPED_UNICODE);
        $res = self::$instance->query("UPDATE coolq_robot SET qq_white_list = '$qq_white_list' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("设置机器人QQ白名单成功：" . json_encode($qq_white_list, JSON_UNESCAPED_UNICODE), Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("设置机器人QQ白名单失败", Plugin::$Plugin, 'SQL');
        }
        return $res;
    }


    /**
     * 获取QQ黑名单
     * @param $QQ
     * @return array|mixed
     */

    public function getQqBlackList($QQ)
    {
        $sql = "SELECT qq_black_list FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if (!$res) {
            Log::Warn("获取机器人QQ黑名单失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $qq_black_list = self::$instance->getOne($res)['qq_black_list'];
        Log::Info("获取机器人QQ黑名单：$qq_black_list", Plugin::$Plugin, 'SQL');
        return isset($qq_black_list) && $qq_black_list != "" ? json_decode($qq_black_list, true) : array();
    }


    /**
     * 设置QQ黑名单
     * @param $QQ
     * @param $group_black_list
     * @return bool
     */
    public function setQqBlackList($QQ, $qq_black_list)
    {
        if (!is_array($qq_black_list)) {
            Log::Warn("设置机器人QQ黑名单失败：传入的参数非数组形式：$qq_black_list", Plugin::$Plugin, 'SQL');
            return false;
        }
        $qq_black_list = json_encode($qq_black_list, JSON_UNESCAPED_UNICODE);
        $res = self::$instance->query("UPDATE coolq_robot SET qq_black_list = '$qq_black_list' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("设置机器人QQ黑名单成功：" . json_encode($qq_black_list, JSON_UNESCAPED_UNICODE), Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("设置机器人QQ黑名单失败", Plugin::$Plugin, 'SQL');
        }
        return $res;
    }


    /**
     * 获取QQ关键字
     * @param $QQ
     * @return array|mixed
     */

    public function getKeyword($QQ)
    {
        $sql = "SELECT keyword FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if (!$res) {
            Log::Warn("获取机器人关键字失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $keyword = self::$instance->getOne($res)['keyword'];
        Log::Info("获取机器人关键字：$keyword", Plugin::$Plugin, 'SQL');
        return isset($keyword) && $keyword != "" ? json_decode($keyword, true) : array();
    }


    /**
     * 设置QQ关键字
     * @param $QQ
     * @param $group_black_list
     * @return bool
     */
    public function setKeyword($QQ, $keyword)
    {
        if (!is_array($keyword)) {
            Log::Warn("设置机器人关键字失败：传入的参数非数组形式：$keyword", Plugin::$Plugin, 'SQL');
            return false;
        }
        $keyword = json_encode($keyword, JSON_UNESCAPED_UNICODE);
        $res = self::$instance->query("UPDATE coolq_robot SET keyword = '$keyword' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("设置机器人关键字成功：" . json_encode($keyword, JSON_UNESCAPED_UNICODE), Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("设置机器人关键字失败", Plugin::$Plugin, 'SQL');
        }
        return $res;
    }


    /**
     * 获取机器人是否开启QQ白名单状态　
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getIsQqWhiteList($QQ)
    {
        $sql = "SELECT is_qq_white_list FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if (!$res) {
            Log::Warn("获取机器人是否开启白名单失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $is_qq_white_list = self::$instance->getOne($res)['is_qq_white_list'];
        Log::Info("获取机器人是否开启QQ白名单状态：$is_qq_white_list", Plugin::$Plugin, 'SQL');
        return $is_qq_white_list;
    }

    /**
     * 设置机器人是否开启QQ白名单状态
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setIsQqWhiteList($QQ, $is_qq_white_list)
    {
        $is_qq_white_list == true ? $is_qq_white_list = 1 : $is_qq_white_list = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET is_qq_white_list = '$is_qq_white_list' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("机器人是否开启QQ白名单状态设置成功：$is_qq_white_list", Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("机器人是否开启QQ白名单状态设置失败", Plugin::$Plugin, 'SQL');
        }
        return $res;
    }


    /**
     * 获取机器人是否开启QQ黑名单状态　
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getIsQqBlackList($QQ)
    {
        $sql = "SELECT is_qq_black_list FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if (!$res) {
            Log::Warn("获取机器人是否开启黑名单失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $is_qq_black_list = self::$instance->getOne($res)['is_qq_black_list'];
        Log::Info("获取机器人是否开启QQ黑名单状态：$is_qq_black_list", Plugin::$Plugin, 'SQL');
        return $is_qq_black_list;
    }

    /**
     * 设置机器人是否开启QQ黑名单状态
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setIsQqBlackList($QQ, $is_qq_black_list)
    {
        $is_qq_black_list == true ? $is_qq_black_list = 1 : $is_qq_black_list = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET is_qq_black_list = '$is_qq_black_list' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("机器人是否开启QQ黑名单状态设置成功：$is_qq_black_list", Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("机器人是否开启QQ黑名单状态设置失败", Plugin::$Plugin, 'SQL');
        }
        return $res;
    }


    /**
     * 获取机器人是否开启群组白名单状态　
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getIsGroupWhiteList($QQ)
    {
        $sql = "SELECT is_group_white_list FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if (!$res) {
            Log::Warn("获取机器人是否开启群组白名单失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $is_group_white_list = self::$instance->getOne($res)['is_group_white_list'];
        Log::Info("获取机器人是否开启群组白名单状态：$is_group_white_list", Plugin::$Plugin, 'SQL');
        return $is_group_white_list;
    }

    /**
     * 设置机器人是否开启群组白名单状态
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setIsGroupWhiteList($QQ, $is_group_white_list)
    {
        $is_group_white_list == true ? $is_group_white_list = 1 : $is_group_white_list = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET is_group_white_list = '$is_group_white_list' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("机器人是否开启QQ白名单状态设置成功：$is_group_white_list", Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("机器人是否开启QQ白名单状态设置失败", Plugin::$Plugin, 'SQL');
        }
        return $res;
    }


    /**
     * 获取机器人是否开启群组黑名单状态　
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getIsGroupBlackList($QQ)
    {
        $sql = "SELECT is_group_black_list FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if (!$res) {
            Log::Warn("获取机器人是否开启群组黑名单失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $is_group_black_list = self::$instance->getOne($res)['is_group_black_list'];
        Log::Info("获取机器人是否开启群组黑名单状态：$is_group_black_list", Plugin::$Plugin, 'SQL');
        return $is_group_black_list;
    }

    /**
     * 设置机器人是否开启群组黑名单状态
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setIsGroupBlackList($QQ, $is_group_black_list)
    {
        $is_group_black_list == true ? $is_group_black_list = 1 : $is_group_black_list = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET is_group_black_list = '$is_group_black_list' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("机器人是否开启群组黑名单状态设置成功：$is_group_black_list", Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("机器人是否开启群组黑名单状态设置失败", Plugin::$Plugin, 'SQL');
        }
        return $res;
    }


    /**
     * 获取机器人是否开启@　
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getIsAt($QQ)
    {
        $sql = "SELECT is_at FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if (!$res) {
            Log::Warn("获取机器人是否开启@失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $is_at = self::$instance->getOne($res)['is_at'];
        Log::Info("获取机器人是否开启@状态：$is_at", Plugin::$Plugin, 'SQL');
        return $is_at;
    }

    /**
     * 设置机器人是是否开启@
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setIsAt($QQ, $is_at)
    {
        $is_at == true ? $is_at = 1 : $is_at = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET is_at = '$is_at' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("机器人是否开启@设置成功：$is_at", Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("机器人是否开启@设置失败", Plugin::$Plugin, 'SQL');
        }
        return $res;
    }


    /**
     * 获取机器人是否开启关键字　
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getIsKeyWord($QQ)
    {
        $sql = "SELECT is_keyword FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if (!$res) {
            Log::Warn("获取机器人是否开启关键字失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $is_keyword = self::$instance->getOne($res)['is_keyword'];
        Log::Info("获取机器人是否开启关键字状态：$is_keyword", Plugin::$Plugin, 'SQL');
        return $is_keyword;
    }

    /**
     * 设置机器人是是否开启关键字
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setIsKeyWord($QQ, $is_keyword)
    {
        $is_keyword == true ? $is_keyword = 1 : $is_keyword = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET is_keyword = '$is_keyword' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("机器人是否开启关键字设置成功：$is_keyword", Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("机器人是否开启关键字设置失败", Plugin::$Plugin, 'SQL');
        }
        return $res;
    }

    /**
     * 获取机器人是否开启好友回复
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getIsOnFriend($QQ)
    {
        $sql = "SELECT is_on_friend FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if (!$res) {
            Log::Warn("获取机器人是否开启好友回复失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $is_on_friend = self::$instance->getOne($res)['is_on_friend'];
        Log::Info("获取机器人是否开启好友回复状态：$is_on_friend", Plugin::$Plugin, 'SQL');
        return $is_on_friend;
    }

    /**
     * 设置机器人是否开启好友回复
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setIsOnFriend($QQ, $is_on_friend)
    {
        $is_on_friend == true ? $is_on_friend = 1 : $is_on_friend = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET is_on_friend = '$is_on_friend' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("机器人是否开启好友回复设置成功：$is_on_friend", Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("机器人是否开启好友回复设置失败", Plugin::$Plugin, 'SQL');
        }
        return $res;
    }

    /**
     * 获取机器人是是否开启群组回复
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getIsOnGroup($QQ)
    {
        $sql = "SELECT is_on_group FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if (!$res) {
            Log::Warn("获取机器人是否开启群组回复失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $is_on_group = self::$instance->getOne($res)['is_on_group'];
        Log::Info("获取机器人是否开启群组回复状态：$is_on_group", Plugin::$Plugin, 'SQL');
        return $is_on_group;
    }

    /**
     * 设置机器人是是否开启群组回复
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setIsOnGroup($QQ, $is_on_group)
    {
        $is_on_group == true ? $is_on_group = 1 : $is_on_group = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET is_on_group = '$is_on_group' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("机器人是否开启群组回复设置成功：$is_on_group", Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("机器人是否开启群组回复设置失败", Plugin::$Plugin, 'SQL');
        }
        return $res;
    }


    /**
     * 获取机器人是是否开启讨论组回复
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getIsOnDiscuss($QQ)
    {
        $sql = "SELECT is_on_discuss FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if (!$res) {
            Log::Warn("获取机器人是否开启讨论组回复失败", Plugin::$Plugin, 'SQL');
            return $res;
        }
        $is_on_discuss = self::$instance->getOne($res)['is_on_discuss'];
        Log::Info("获取机器人是否开启讨论组回复状态：$is_on_discuss", Plugin::$Plugin, 'SQL');
        return $is_on_discuss;
    }

    /**
     * 设置机器人是是否开启讨论组回复
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setIsOnDiscuss($QQ, $is_on_discuss)
    {
        $is_on_discuss == true ? $is_on_discuss = 1 : $is_on_discuss = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET is_on_discuss = '$is_on_discuss' WHERE qq = '$QQ'");
        if ($res) {
            Log::Info("机器人是否开启讨论组回复设置成功：$is_on_discuss", Plugin::$Plugin, 'SQL');
        } else {
            Log::Warn("机器人是否开启讨论组回复设置失败", Plugin::$Plugin, 'SQL');
        }
        return $res;
    }


    /**
     * 获取机器人是是否开启同意好友请求
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getIsOnAgreeFriend($QQ)
    {
        $sql = "SELECT is_agree_friend FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if(!$res){
            Log::Warn("获取机器人是否开启同意好友请求失败",Plugin::$Plugin,'SQL');
            return $res;
        }
        $is_agree_friend = self::$instance->getOne($res)['is_agree_friend'];
        Log::Info("获取机器人是否开启同意好友请求状态：$is_agree_friend",Plugin::$Plugin,'SQL');
        return $is_agree_friend;
    }

    /**
     * 设置机器人是是否开启同意好友请求
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setIsOnAgreeFriend($QQ,$is_agree_friend)
    {
        $is_agree_friend == true ? $is_agree_friend = 1 : $is_agree_friend = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET is_agree_friend = '$is_agree_friend' WHERE qq = '$QQ'");
        if($res){
            Log::Info("机器人是否开启同意好友请求设置成功：$is_agree_friend",Plugin::$Plugin,'SQL');
        }else{
            Log::Warn("机器人是否开启同意好友请求设置失败",Plugin::$Plugin,'SQL');
        }
        return $res;
    }

    /**
     * 设置机器人是是否开启同意加群请求
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getIsOnAgreeGroup($QQ)
    {
        $sql = "SELECT is_agree_group FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if(!$res){
            Log::Warn("获取机器人是否开启同意加群请求失败",Plugin::$Plugin,'SQL');
            return $res;
        }
        $is_agree_group = self::$instance->getOne($res)['is_agree_group'];
        Log::Info("获取机器人是否开启同意加群请求状态：$is_agree_group",Plugin::$Plugin,'SQL');
        return $is_agree_group;
    }

    /**
     * 设置机器人是是否开启同意加群请求
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setIsOnAgreeGroup($QQ,$is_agree_group)
    {
        $is_agree_group == true ? $is_agree_group = 1 : $is_agree_group = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET is_agree_group = '$is_agree_group' WHERE qq = '$QQ'");
        if($res){
            Log::Info("机器人是否开启同意加群请求设置成功：$is_agree_group",Plugin::$Plugin,'SQL');
        }else{
            Log::Warn("机器人是否开启同意加群请求设置失败",Plugin::$Plugin,'SQL');
        }
        return $res;
    }


    /**
     * 获取机器人是是否开启Debug跟随
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getIsFollow($QQ)
    {
        $sql = "SELECT is_follow FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if(!$res){
            Log::Warn("获取机器人是否开启Debug跟随失败",Plugin::$Plugin,'SQL');
            return $res;
        }
        $is_follow = self::$instance->getOne($res)['is_follow'];
        Log::Info("获取机器人是否开启Debug跟随状态：$is_follow",Plugin::$Plugin,'SQL');
        return $is_follow;
    }

    /**
     * 设置机器人是是否开启Debug跟随
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setIsFollow($QQ,$is_follow)
    {
        $is_follow == true ? $is_follow = 1 : $is_follow = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET is_follow = '$is_follow' WHERE qq = '$QQ'");
        if($res){
            Log::Info("机器人是否开启Debug跟随设置成功：$is_follow",Plugin::$Plugin,'SQL');
        }else{
            Log::Warn("机器人是否开启Debug跟随设置失败",Plugin::$Plugin,'SQL');
        }
        return $res;
    }

    /**
     * 获取机器人是是否开启插件
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getIsOnPlugin($QQ)
    {
        $sql = "SELECT is_on_plugin FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if(!$res){
            Log::Warn("获取机器人是否开启插件失败",Plugin::$Plugin,'SQL');
            return $res;
        }
        $is_on_plugin = self::$instance->getOne($res)['is_on_plugin'];
        Log::Info("获取机器人是否开启插件状态：$is_on_plugin",Plugin::$Plugin,'SQL');
        return $is_on_plugin;
    }

    /**
     * 设置机器人是是否开启插件
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setIsOnPlugin($QQ,$is_on_plugin)
    {
        $is_on_plugin == true ? $is_on_plugin = 1 : $is_on_plugin = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET is_on_plugin = '$is_on_plugin' WHERE qq = '$QQ'");
        if($res){
            Log::Info("机器人是否开启插件设置成功：$is_on_plugin",Plugin::$Plugin,'SQL');
        }else{
            Log::Warn("机器人是否开启插件设置失败",Plugin::$Plugin,'SQL');
        }
        return $res;
    }

    /**
     * 获取机器人是否开启回复at
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getIsReplyAt($QQ)
    {
        $sql = "SELECT is_reply_at FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if(!$res){
            Log::Warn("设置机器人是是否开启回复at失败",Plugin::$Plugin,'SQL');
            return $res;
        }
        $is_reply_at = self::$instance->getOne($res)['is_reply_at'];
        Log::Info("设置机器人是是否开启回复at状态：$is_reply_at",Plugin::$Plugin,'SQL');
        return $is_reply_at;
    }

    /**
     * 设置机器人是是否开启回复at
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setIsReplyAt($QQ,$is_reply_at)
    {
        $is_reply_at == true ? $is_reply_at = 1 : $is_reply_at = 0;
        $res = self::$instance->query("UPDATE coolq_robot SET is_reply_at = '$is_reply_at' WHERE qq = '$QQ'");
        if($res){
            Log::Info("设置机器人是是否开启回复at成功：$is_reply_at",Plugin::$Plugin,'SQL');
        }else{
            Log::Warn("设置机器人是是否开启回复at失败",Plugin::$Plugin,'SQL');
        }
        return $res;
    }

    /**
     * 获取机器人QQ拒绝好友请求理由
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getQQRefuseReason($QQ)
    {
        $sql = "SELECT qq_refuse_reason FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if(!$res){
            Log::Warn("获取机器人QQ拒绝好友请求理由失败",Plugin::$Plugin,'SQL');
            return $res;
        }
        $qq_refuse_reason = self::$instance->getOne($res)['qq_refuse_reason'];
        Log::Info("获取机器人QQ拒绝好友请求理由：$qq_refuse_reason",Plugin::$Plugin,'SQL');
        return $qq_refuse_reason;
    }

    /**
     * 设置机器人QQ拒绝好友请求理由
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setQQRefuseReason($QQ,$qq_refuse_reason)
    {

        $res = self::$instance->query("UPDATE coolq_robot SET qq_refuse_reason = '$qq_refuse_reason' WHERE qq = '$QQ'");
        if($res){
            Log::Info("机器人设置机器人QQ拒绝好友请求理由成功：$qq_refuse_reason",Plugin::$Plugin,'SQL');
        }else{
            Log::Warn("机器人设置机器人QQ拒绝好友请求理由失败",Plugin::$Plugin,'SQL');
        }
        return $res;
    }

    /**
     * 获取机器人群组拒绝申请请求理由
     * 比较状态时注意　＝＝＝　　比较
     * @return mixed
     */
    public function getGroupRefuseReason($QQ)
    {
        $sql = "SELECT group_refuse_reason FROM coolq_robot WHERE qq = '$QQ'";
        $res = self::$instance->query($sql);
        if(!$res){
            Log::Warn("获取机器人群组拒绝申请请求理由失败",Plugin::$Plugin,'SQL');
            return $res;
        }
        $group_refuse_reason = self::$instance->getOne($res)['group_refuse_reason'];
        Log::Info("获取机器人群组拒绝申请请求理由：$group_refuse_reason",Plugin::$Plugin,'SQL');
        return $group_refuse_reason;
    }

    /**
     * 设置机器人群组拒绝申请请求理由
     * @param $QQ
     * @param $status
     * @return mixed
     */
    public function setGroupRefuseReason($QQ,$group_refuse_reason)
    {
        $res = self::$instance->query("UPDATE coolq_robot SET group_refuse_reason = '$group_refuse_reason' WHERE qq = '$QQ'");
        if($res){
            Log::Info("设置机器人群组拒绝申请请求理由成功：$group_refuse_reason",Plugin::$Plugin,'SQL');
        }else{
            Log::Warn("设置机器人群组拒绝申请请求理由失败",Plugin::$Plugin,'SQL');
        }
        return $res;
    }


}