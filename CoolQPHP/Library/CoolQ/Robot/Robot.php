<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/5 0005
 * Time: 14:39
 */

namespace CoolQ\Robot;

use CoolQ\Log;
use CoolQSDK\CoolQSDK;
use CoolQ\Db\RobotSql;
use CoolQ\Config;
use CoolQ\Plugin\Plugin;

class Robot
{

    protected static $instance = null;
    protected static $RobotSqlinstance = null;
    protected $path;
    protected $CoolQ;
    public $CreateTime;
    public $status;
    public $manager;
    public $group_white_list;
    public $group_black_list;
    public $qq_white_list;
    public $qq_black_list;
    public $keyword;
    public $is_group_white_list;
    public $is_group_black_list;
    public $is_qq_white_list;
    public $is_qq_black_list;
    public $is_at;
    public $is_keyword;
    public $is_follow;
    public $is_on_friend;
    public $is_on_group;
    public $is_on_discuss;
    public $is_agree_friend;
    public $is_agree_group;
    public $is_on_plugin;
    public $group_refuse_reason;
    public $qq_refuse_reason;
    public $is_reply_at;

    /**
     * Robot constructor.
     * @param $HOST
     * @param $PORT
     * @param $TOKEN
     */
    protected function __construct($HOST, $PORT, $TOKEN)
    {
        self::$RobotSqlinstance = RobotSql::getInstance(Config::getDbConfig());
        $this->host = $HOST;
        $this->port = $PORT;
        $this->token = $TOKEN;
        $this->path = $this->host . ':' . $this->port;
        $this->CoolQ = new CoolQSDK($HOST, $PORT, $TOKEN);
        $this->QQ = Config::getQQ();

    }

    protected function __clone()
    {
    }

    protected function __wakeup()
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
    public static function runPlugin($plugin_class_name)
    {
        $Plugin = null;
//        echo $plugin_class_name . "<br>";
        include_once ROOT_PATH . "Plugin/$plugin_class_name/" . $plugin_class_name . ".php";
        eval("@\$Plugin = new " . $plugin_class_name . "();");
        if ($Plugin == null) return null;
        @$Plugin->Start();
        return @$Plugin;
    }


    public function getRobotPluginOrders()
    {
        $sql = "SELECT coolq_plugin_list.name,coolq_plugin_orders.plugin_id,coolq_plugin_orders.order_name,coolq_plugin_list.priority,coolq_plugin_list.status,coolq_plugin_list.plugin_class FROM coolq_plugin_orders,coolq_plugin_list WHERE  coolq_plugin_orders.	plugin_id = coolq_plugin_list.id   ORDER BY  coolq_plugin_list.priority DESC ";
        $rs = self::$RobotSqlinstance->query($sql);
        $array = array();
        while ($r = self::$RobotSqlinstance->getOne($rs)) {
            $array[] = $r;
        }
        return $array;
    }

    public function getRobotInfo()
    {
        $res = self::$RobotSqlinstance->getRobot($this->QQ);
        $CreateTime = $res['time'];
        $this->status = $res['status'];
        $this->manager = isset($res['manager']) && $res['manager'] != "" ? json_decode($res['manager'], true) : array();
        $res['manager'] = isset($res['manager']) && $res['manager'] != "" ? json_decode($res['manager'], true) : array();
        $this->group_white_list = isset($res['group_white_list']) && $res['group_white_list'] != "" ? json_decode($res['group_white_list'], true) : array();
        $res['group_white_list'] = isset($res['group_white_list']) && $res['group_white_list'] != "" ? json_decode($res['group_white_list'], true) : array();
        $this->group_black_list = isset($res['group_black_list']) && $res['group_black_list'] != "" ? json_decode($res['group_black_list'], true) : array();
        $res['group_black_list'] = isset($res['group_black_list']) && $res['group_black_list'] != "" ? json_decode($res['group_black_list'], true) : array();
        $this->qq_white_list = isset($res['qq_white_list']) && $res['qq_white_list'] != "" ? json_decode($res['qq_white_list'], true) : array();
        $res['qq_white_list'] = isset($res['qq_white_list']) && $res['qq_white_list'] != "" ? json_decode($res['qq_white_list'], true) : array();
        $this->qq_black_list = isset($res['qq_black_list']) && $res['qq_black_list'] != "" ? json_decode($res['qq_black_list'], true) : array();
        $res['qq_black_list'] = isset($res['qq_black_list']) && $res['qq_black_list'] != "" ? json_decode($res['qq_black_list'], true) : array();
        $res['keyword'] = isset($res['keyword']) && $res['keyword'] != "" ? json_decode($res['keyword'], true) : array();
        $this->keyword = $res['keyword'];
        $this->is_group_white_list = $res['is_group_white_list'];
        $this->is_group_black_list = $res['is_group_black_list'];
        $this->is_qq_white_list = $res['is_qq_white_list'];
        $this->is_qq_black_list = $res['is_qq_black_list'];
        $this->is_at = $res['is_at'];
        $this->is_keyword = $res['is_keyword'];
        $this->is_follow = $res['is_follow'];
        $this->is_on_friend = $res['is_on_friend'];
        $this->is_on_group = $res['is_on_group'];
        $this->is_on_discuss = $res['is_on_discuss'];
        $this->is_agree_friend = $res['is_agree_friend'];
        $this->is_agree_group = $res['is_agree_group'];
        $this->is_on_plugin = $res['is_on_plugin'];
        $this->group_refuse_reason = $res['group_refuse_reason'];
        $this->qq_refuse_reason = $res['qq_refuse_reason'];
        $this->is_reply_at = $res['is_reply_at'];
        return $res;

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
        $this->CreateTime = self::$RobotSqlinstance->getCreateTime($this->QQ);
        return $this->CreateTime;
    }


    /**
     * @return mixed
     */
    public function getRobotStatus()
    {
        $this->status = self::$RobotSqlinstance->getStatus($this->QQ);
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setRobotStatus($status)
    {
        self::$RobotSqlinstance->setStatus($this->QQ, $status);
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getRobotManager()
    {
        $this->manager = self::$RobotSqlinstance->getManage($this->QQ);
        return $this->manager;
    }

    /**
     * @param mixed $manager
     */
    public function setRobotManager($manager)
    {
        self::$RobotSqlinstance->setManager($this->QQ, $manager);
        $this->manager = $manager;
    }

    /**
     * @return mixed
     */
    public function getRobotGroupWhiteList()
    {
        $this->group_white_list = self::$RobotSqlinstance->getGroupWhiteList($this->QQ);
        return $this->group_white_list;
    }

    /**
     * @param mixed $group_white_list
     */
    public function setRobotGroupWhiteList($group_white_list)
    {
        self::$RobotSqlinstance->setGroupWhiteList($this->QQ, $group_white_list);
        $this->group_white_list = $group_white_list;
    }

    /**
     * @return mixed
     */
    public function getRobotGroupBlackList()
    {
        $this->group_black_list = self::$RobotSqlinstance->getGroupBlackList($this->QQ);
        return $this->group_black_list;
    }

    /**
     * @param mixed $group_black_list
     */
    public function setRobotGroupBlackList($group_black_list)
    {
        self::$RobotSqlinstance->setGroupBlackList($this->QQ, $group_black_list);
        $this->group_black_list = $group_black_list;
    }

    /**
     * @return mixed
     */
    public function getRobotQqWhiteList()
    {
        $this->qq_white_list = self::$RobotSqlinstance->getQqWhiteList($this->QQ);
        return $this->qq_white_list;
    }

    /**
     * @param mixed $qq_white_list
     */
    public function setRobotQqWhiteList($qq_white_list)
    {
        self::$RobotSqlinstance->setQqWhiteList($this->QQ, $qq_white_list);
        $this->qq_white_list = $qq_white_list;
    }

    /**
     * @return mixed
     */
    public function getRobotQqBlackList()
    {
        $this->qq_black_list = self::$RobotSqlinstance->getQqBlackList($this->QQ);
        return $this->qq_black_list;
    }

    /**
     * @param mixed $qq_black_list
     */
    public function setRobotQqBlackList($qq_black_list)
    {
        self::$RobotSqlinstance->setQqBlackList($this->QQ, $qq_black_list);
        $this->qq_black_list = $qq_black_list;
    }

    /**
     * @return mixed
     */
    public function getRobotKeyword()
    {
        $this->keyword = self::$RobotSqlinstance->getKeyword($this->QQ);
        return $this->keyword;
    }

    /**
     * @param mixed $keyword
     */
    public function setRobotKeyword($keyword)
    {
        self::$RobotSqlinstance->setKeyword($this->QQ, $keyword);
        $this->keyword = $keyword;
    }


    /**
     * @return mixed
     */
    public function getRobotQQRefuseReason()
    {
        $this->qq_refuse_reason = self::$RobotSqlinstance->getQQRefuseReason($this->QQ);
        return $this->qq_refuse_reason;
    }

    /**
     * @param mixed $qq_refuse_reason
     */
    public function setRobotQQRefuseReason($qq_refuse_reason)
    {
        self::$RobotSqlinstance->setQQRefuseReason($this->QQ, $qq_refuse_reason);
        $this->qq_refuse_reason = $qq_refuse_reason;
    }

    /**
     * @return mixed
     */
    public function getRobotGroupRefuseReason()
    {
        $this->group_refuse_reason = self::$RobotSqlinstance->getGroupRefuseReason($this->QQ);
        return $this->group_refuse_reason;
    }

    /**
     * @param mixed $group_refuse_reason
     */
    public function setRobotGroupRefuseReason($group_refuse_reason)
    {
        self::$RobotSqlinstance->setGroupRefuseReason($this->QQ, $group_refuse_reason);
        $this->group_refuse_reason = $group_refuse_reason;
    }

    /**
     * @return mixed
     */
    public function getRobotIsGroupWhiteList()
    {
        $this->is_group_white_list = self::$RobotSqlinstance->getIsGroupWhiteList($this->QQ);
        return $this->is_group_white_list;
    }

    /**
     * @param mixed $is_group_white_list
     */
    public function setRobotIsGroupWhiteList($is_group_white_list)
    {
        self::$RobotSqlinstance->setIsGroupWhiteList($this->QQ, $is_group_white_list);
        $this->is_group_white_list = $is_group_white_list;
    }

    /**
     * @return mixed
     */
    public function getRobotIsGroupBlackList()
    {
        $this->is_group_black_list = self::$RobotSqlinstance->getIsGroupBlackList($this->QQ);
        return $this->is_group_black_list;
    }

    /**
     * @param mixed $is_group_black_list
     */
    public function setRobotIsGroupBlackList($is_group_black_list)
    {
        self::$RobotSqlinstance->setIsGroupBlackList($this->QQ, $is_group_black_list);
        $this->is_group_black_list = $is_group_black_list;
    }

    /**
     * @return mixed
     */
    public function getRobotIsQqWhiteList()
    {
        $this->is_qq_white_list = self::$RobotSqlinstance->getIsQqWhiteList($this->QQ);
        return $this->is_qq_white_list;
    }

    /**
     * @param mixed $is_qq_white_list
     */
    public function setRobotIsQqWhiteList($is_qq_white_list)
    {
        self::$RobotSqlinstance->setIsQqWhiteList($this->QQ, $is_qq_white_list);
        $this->is_qq_white_list = $is_qq_white_list;
    }

    /**
     * @return mixed
     */
    public function getRobotIsQqBlackList()
    {
        $this->is_qq_black_list = self::$RobotSqlinstance->getIsQqBlackList($this->QQ);
        return $this->is_qq_black_list;
    }

    /**
     * @param mixed $is_qq_black_list
     */
    public function setRobotIsQqBlackList($is_qq_black_list)
    {
        self::$RobotSqlinstance->setIsQqBlackList($this->QQ, $is_qq_black_list);
        $this->is_qq_black_list = $is_qq_black_list;
    }

    /**
     * @return mixed
     */
    public function getRobotIsAt()
    {
        $this->is_at = self::$RobotSqlinstance->getIsAt($this->QQ);
        return $this->is_at;
    }

    /**
     * @param mixed $is_at
     */
    public function setRobotIsAt($is_at)
    {
        self::$RobotSqlinstance->setIsAt($this->QQ, $is_at);
        $this->is_at = $is_at;
    }

    /**
     * @return mixed
     */
    public function getRobotIsKeyword()
    {
        $this->is_keyword = self::$RobotSqlinstance->getIsKeyWord($this->QQ);
        return $this->is_keyword;
    }

    /**
     * @param mixed $is_keyword
     */
    public function setRobotIsKeyword($is_keyword)
    {
        self::$RobotSqlinstance->setIsKeyword($this->QQ, $is_keyword);
        $this->is_keyword = $is_keyword;
    }

    /**
     * @return mixed
     */
    public function getRobotIsFollow()
    {
        $this->is_follow = self::$RobotSqlinstance->getIsFollow($this->QQ);
        return $this->is_follow;
    }

    /**
     * @param mixed $is_follow
     */
    public function setRobotIsFollow($is_follow)
    {
        self::$RobotSqlinstance->setIsFollow($this->QQ, $is_follow);
        $this->is_follow = $is_follow;
    }

    /**
     * @return mixed
     */
    public function getRobotIsOnFriend()
    {
        $this->is_on_friend = self::$RobotSqlinstance->getIsOnFriend($this->QQ);
        return $this->is_on_friend;
    }

    /**
     * @param mixed $is_on_friend
     */
    public function setRobotIsOnFriend($is_on_friend)
    {
        self::$RobotSqlinstance->setIsOnFriend($this->QQ, $is_on_friend);
        $this->is_on_friend = $is_on_friend;
    }

    /**
     * @return mixed
     */
    public function getRobotIsOnGroup()
    {
        $this->is_on_group = self::$RobotSqlinstance->getIsOnGroup($this->QQ);
        return $this->is_on_group;
    }

    /**
     * @param mixed $is_on_group
     */
    public function setRobotIsOnGroup($is_on_group)
    {
        self::$RobotSqlinstance->setIsOnGroup($this->QQ, $is_on_group);
        $this->is_on_group = $is_on_group;
    }

    /**
     * @return mixed
     */
    public function getRobotIsOnDiscuss()
    {
        $this->is_on_discuss = self::$RobotSqlinstance->getIsOnDiscuss($this->QQ);
        return $this->is_on_discuss;
    }

    /**
     * @param mixed $is_on_discuss
     */
    public function setRobotIsOnDiscuss($is_on_discuss)
    {
        self::$RobotSqlinstance->setIsOnDiscuss($this->QQ, $is_on_discuss);
        $this->is_on_discuss = $is_on_discuss;
    }

    /**
     * @return mixed
     */
    public function getRobotIsAgreeFriend()
    {
        $this->is_agree_friend = self::$RobotSqlinstance->getIsOnAgreeFriend($this->QQ);
        return $this->is_agree_friend;
    }

    /**
     * @param mixed $is_agree_friend
     */
    public function setRobotIsAgreeFriend($is_agree_friend)
    {
        self::$RobotSqlinstance->setIsOnAgreeFriend($this->QQ, $is_agree_friend);
        $this->is_agree_friend = $is_agree_friend;
    }

    /**
     * @return mixed
     */
    public function getRobotIsAgreeGroup()
    {
        $this->is_agree_group = self::$RobotSqlinstance->getIsOnAgreeGroup($this->QQ);
        return $this->is_agree_group;
    }

    /**
     * @param mixed $is_agree_group
     */
    public function setRobotIsAgreeGroup($is_agree_group)
    {
        self::$RobotSqlinstance->setIsOnAgreeGroup($this->QQ, $is_agree_group);
        $this->is_agree_group = $is_agree_group;
    }

    /**
     * @return mixed
     */
    public function getRobotIsOnPlugin()
    {
        $this->is_on_plugin = self::$RobotSqlinstance->getIsOnPlugin($this->QQ);
        return $this->is_on_plugin;
    }

    /**
     * @param mixed $is_on_plugin
     */
    public function setRobotIsOnPlugin($is_on_plugin)
    {
        self::$RobotSqlinstance->setIsOnPlugin($this->QQ, $is_on_plugin);
        $this->is_on_plugin = $is_on_plugin;
    }

    /**
     * @return mixed
     */
    public function getRobotIsReplyAt()
    {
        $this->is_reply_at = self::$RobotSqlinstance->getIsReplyAt($this->QQ);
        return $this->is_reply_at;
    }

    /**
     * @param mixed $is_reply_at
     */
    public function setRobotIsReplyAt($is_reply_at)
    {
        self::$RobotSqlinstance->setIsReplyAt($this->QQ, $is_reply_at);
        $this->is_reply_at = $is_reply_at;
    }



    public function getRobotSqlnstance()
    {
        return self::$RobotSqlinstance;
    }





    /**
     *  CoolQ
     */


    /**
     * /get_login_info 获取登录号信息
     *   参数
     *   无
     *   响应数据
     *   字段名    数据类型    说明
     *   user_id    number    QQ 号
     *   nickname    string    QQ 昵称
     * @return string json
     * {
     *       "status": "ok",
     *       "retcode": 0,
     *       "data": {
     *       "user_id": 1246002938,
     *       "nickname": "机器人不知道多少代了"
     *       }
     *    }
     */
    public function getLoginInfo()
    {
        $res =  $this->CoolQ->getLoginInfo();
        Log::Info('获取登录号信息 :' . $res,Plugin::$Plugin);
        return $res;
    }


    /**
     * /send_private_msg 发送私聊消息
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $user_id  number    -    对方 QQ 号
     * @param $message  string/array    -    要发送的内容
     * @param string $is_raw bool    false    消息内容是否作为纯文本发送（即不解析 CQ 码），message 数据类型为 array 时无效
     * @return mixed|string
     */
    public function sendPrivateMsg($user_id, $message, $is_raw = 'false')
    {
        $res =  $this->CoolQ->sendPrivateMsg($user_id, $message, $is_raw);
        Log::Info('发送私聊'. $user_id .'消息 :'. $message .'  是否转译:'. $is_raw .'' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /send_group_msg 发送群消息
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $group_id number    -    群号
     * @param $message  string/array    -    要发送的内容
     * @param string $is_raw bool    false    消息内容是否作为纯文本发送（即不解析 CQ 码），message 数据类型为 array 时无效
     * @return mixed|string
     */
    public function sendGroupMsg($group_id, $message, $is_raw = 'false')
    {
        $res =  $this->CoolQ->sendGroupMsg($group_id, $message, $is_raw);
        Log::Info('发送群'. $group_id .'消息 :'. $message .' 是否转译 :'. $is_raw .'' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /send_discuss_msg 发送讨论组消息
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $discuss_id   number    -    讨论组 ID（正常情况下看不到，需要从讨论组消息上报的数据中获得）
     * @param $message  string/array    -    要发送的内容
     * @param string $is_raw bool    false    消息内容是否作为纯文本发送（即不解析 CQ 码），message 数据类型为 array 时无效
     * @return mixed|string
     */
    public function sendDiscussMsg($discuss_id, $message, $is_raw = 'false')
    {
        $res =  $this->CoolQ->sendDiscussMsg($discuss_id, $message, $is_raw);
        Log::Info('发送讨论组'. $discuss_id .'消息 :'. $message .'  是否转译:  '. $is_raw .' ' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /send_like 发送好友赞
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $user_id  number    -    对方 QQ 号
     * @param int $times number    1    赞的次数，每个好友每天最多 10 次
     * @return mixed|string
     */
    public function sendLike($user_id, $times = 1)
    {
        $res =  $this->CoolQ->sendLike($user_id, $times);
        Log::Info('发送好友'. $user_id .'赞 :'. $times .'' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /set_group_kick 群组踢人
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $group_id number    -    群号
     * @param $user_id number    -    要踢的 QQ 号
     * @param string $reject_add_request bool    false    拒绝此人的加群请求
     * @return mixed|string
     */
    public function setGroupKick($group_id, $user_id, $reject_add_request = 'false')
    {
        $res =  $this->CoolQ->setGroupKick($group_id, $user_id, $reject_add_request);
        Log::Info('群组'. $group_id .'踢人'. $user_id .' : 拒绝下次请求 :'. $reject_add_request .'' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /set_group_ban 群组单人禁言
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $group_id number    -    群号
     * @param $user_id  number    -    要禁言的 QQ 号
     * @param int $duration number    30 * 60    禁言时长，单位秒，0 表示取消禁言
     * @return mixed|string
     */
    public function setGroupBan($group_id, $user_id, $duration = 30)
    {
        $res =  $this->CoolQ->setGroupBan($group_id, $user_id, $duration);
        Log::Info('群组'. $group_id .'单人'. $user_id .'禁言 :'. $duration .'' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /set_group_whole_ban 群组全员禁言
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $group_id number    -    群号
     * @param string $enable bool    true    是否禁言
     * @return mixed|string
     */
    public function setGroupWholeBan($group_id, $enable = 'true')
    {
        $res =  $this->CoolQ->setGroupWholeBan($group_id, $enable);
        Log::Info('群组'. $group_id .'全员禁言 :'. $enable .'' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /set_group_anonymous_ban 群组匿名用户禁言
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $group_id number    -    群号
     * @param $flag string    -    要禁言的匿名用户的 flag（需从群消息上报的数据中获得）
     * @param int $duration number    30 * 60    禁言时长，单位秒，无法取消匿名用户禁言
     * @return mixed|string
     */
    public function setGroupAnonymousBan($group_id, $flag, $duration = 30)
    {
        $res =  $this->CoolQ->setGroupAnonymousBan($group_id, $flag, $duration);
        Log::Info('群组'. $group_id .'匿名用户'. $flag .'禁言 :'. $duration .'  ' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /set_group_admin 群组设置管理员
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $group_id number    -    群号
     * @param $user_id  number    -    要设置管理员的 QQ 号
     * @param string $enable bool    true    true 为设置，false 为取消
     * @return mixed|string
     */
    public function setGroupAdmin($group_id, $user_id, $enable = 'true')
    {
        $res =  $this->CoolQ->setGroupAdmin($group_id, $user_id, $enable);
        Log::Info('群组'. $group_id .'设置管理员 '. $user_id .' 设置 :'. $enable .'' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /set_group_anonymous 群组匿名
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $group_id number    -    群号
     * @param string $enable bool    true    是否允许匿名聊天
     * @return mixed|string
     */
    public function setGroupAnonymous($group_id, $enable = 'true')
    {
        $res =  $this->CoolQ->setGroupAnonymous($group_id, $enable);
        Log::Info('群组'. $group_id .'匿名 : '. $enable .'' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /set_group_special_title 设置群组专属头衔
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $group_id number    -    群号
     * @param $user_id  number    -    要设置的 QQ 号
     * @param string $special_title string    空    专属头衔，不填或空字符串表示删除专属头衔
     * @param int $duration number    -1    专属头衔有效期，单位秒，-1 表示永久，不过此项似乎没有效果，可能是只有某些特殊的时间长度有效，有待测试
     * @return mixed|string
     */
    public function setGroupSpecialTitle($group_id, $user_id, $special_title = "", $duration = -1)
    {
        $res =  $this->CoolQ->setGroupSpecialTitle($group_id, $user_id, $special_title, $duration);
        Log::Info('设置'. $group_id .'群组'. $user_id .'专属头衔'. $special_title .' 永久 '. $duration .' '. $group_id .' :' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /set_group_card 设置群名片（群备注）
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $group_id number    -    群号
     * @param $user_id  number    -    要设置的 QQ 号
     * @param $card string    空    群名片内容，不填或空字符串表示删除群名片
     * @return mixed|string
     */
    public function setGroupCard($group_id, $user_id, $card)
    {
        $res =  $this->CoolQ->setGroupCard($group_id, $user_id, $card);
        Log::Info('设置'. $group_id .' 群名片 '. $user_id .'  （群备注）'. $card .'  :' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /set_group_leave 退出群组
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $group_id number    -    群号
     * @param string $is_dismiss bool    false    是否解散，如果登录号是群主，则仅在此项为 true 时能够解散
     * @return mixed|string
     */
    public function setGroupLeave($group_id, $is_dismiss = 'false')
    {
        $res =  $this->CoolQ->setGroupLeave($group_id, $is_dismiss);
        Log::Info('退出'. $group_id .' 群组 解散'. $is_dismiss .'  :' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /set_discuss_leave 退出讨论组衔
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $discuss_id   number    -    讨论组 ID（正常情况下看不到，需要从讨论组消息上报的数据中获得）
     * @return mixed|string
     */
    public function setDiscussLeave($discuss_id)
    {
        $res =  $this->CoolQ->setDiscussLeave($discuss_id);
        Log::Info('退出'. $discuss_id .' 讨论组 :' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /set_friend_add_request 处理加好友请求
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $flag  string    -    加好友请求的 flag（需从上报的数据中获得）
     * @param string $approve bool    true    是否同意请求
     * @param string $remark string    空    添加后的好友备注（仅在同意时有效）
     * @return mixed|string
     */
    public function setFriendAddRequest($flag, $approve = 'true', $remark = "")
    {
        $res = $this->CoolQ->setFriendAddRequest($flag, $approve, $remark);
        Log::Info('处理 '. $flag .'  加好友请求 '. $approve .'  备注  '. $remark .'   :' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /set_group_add_request 处理加群请求／邀请
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $flag string    -    加好友请求的 flag（需从上报的数据中获得）
     * @param $type string    -    add 或 invite，请求类型（需要和上报消息中的 sub_type 字段相符）
     * @param string $approve bool    true    是否同意请求／邀请
     * @param string $reason string    空    拒绝理由（仅在拒绝时有效）
     * @return mixed|string
     */
    public function setGroupAddRequest($flag, $type, $approve = 'true', $reason = "")
    {
        $res = $this->CoolQ->setGroupAddRequest($flag, $type, $approve, $reason);
        Log::Info('处理 '. $flag .'  '. $type .' 群 '. $approve .' 理由: '. $reason .'  :' . $res,Plugin::$Plugin);
        return $res;
    }


    /**
     * /get_group_member_list 获取群列表
     *   参数
     *        字段名    数据类型    默认值    说明
     * @return mixed|string
     */
    public function getGroupList()
    {
        $res = $this->CoolQ->getGroupList();
        Log::Info('获取群列表 :' . $res,Plugin::$Plugin);
        return $res;
    }


    /**
     * /get_group_member_list 获取群成员列表
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $group_id number    -    群号
     * @return mixed|string
     */
    public function getGroupMemberList($group_id)
    {
        $res = $this->CoolQ->getGroupMemberList($group_id);
        Log::Info('获取 '. $group_id .' 群成员列表 :' . $res,Plugin::$Plugin);
        return $res;
    }


    /**
     * /get_group_member_info 获取群成员信息
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $group_id number    -    群号
     * @param $user_id  number    -    QQ 号（不可以是登录号）
     * @param string $no_cache bool    false    是否不使用缓存（使用缓存可能更新不及时，但响应更快）
     * @return mixed|string
     */
    public function getGroupMemberInfo($group_id, $user_id, $no_cache = 'false')
    {
        $res =  $this->CoolQ->getGroupMemberInfo($group_id, $user_id, $no_cache);
        Log::Info('获取 '. $group_id .' 群成员 '. $user_id .' 信息 :' . $res,Plugin::$Plugin);
        return $res;
    }


    /**
     * /get_stranger_info 获取陌生人信息
     *   参数
     *        字段名    数据类型    默认值    说明
     * @param $user_id  number    -    QQ 号（不可以是登录号）
     * @param string $no_cache bool    false    是否不使用缓存（使用缓存可能更新不及时，但响应更快）
     * @return mixed|string
     */
    public function getStrangerInfo($user_id, $no_cache = 'false')
    {
        $res =  $this->CoolQ->getStrangerInfo($user_id, $no_cache);
        Log::Info('获取 '. $user_id .' 陌生人信息 :' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /get_cookies 获取 Cookies
     * @return mixed|string
     */
    public function getCookies()
    {
        $res =  $this->CoolQ->getCookies();
        Log::Info('获取Cookies :' . $res,Plugin::$Plugin);
        return $res;
    }

    /**
     * /get_csrf_token 获取 CSRF Token
     * @return mixed|string
     */
    public function getCsrfToken()
    {
        $res = $this->CoolQ->getCsrfToken();
        Log::Info('获取 CSRF Token :' . $res,Plugin::$Plugin);
        return $res;
    }


}