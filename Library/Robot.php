<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/23
 * Time: 1:45
 */

namespace Library;

use CoolQSDK\CoolQSDK;
use CoolQSDK\CQ;
use Library\Config;

class Robot
{
    private $Mysql;
    private $QQ;
    private static $instance;
    private $CoolQ;
    private $host;
    private $port;
    private $token;
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
    private $group_refuse_reason;
    private $qq_refuse_reason;
    private $is_reply_at;
    public static $Debug = false;
    public  $isSend = OFF;

    protected function __construct($QQ)
    {
        $this->QQ = $QQ;
        $this->Mysql = Mysql::getInstance(\Library\Config::getDbConfig());
        self::init();

    }

    protected function __clone()
    {
    }

    protected function __wakeup()
    {
    }

    public function init()
    {
        $sql = "select * from coolq_robot WHERE qq = $this->QQ";
        $res = $this->Mysql->query($sql);
        $res = $this->Mysql->getOne($res);
        $this->host = $res['host'];
        $this->port = $res['port'];
        $this->token = $res['token'];
        $this->status = $res['status'];
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
//        $this->manager = $this->_getManager();
//        $this->group_white_list = $this->_getGroupWhiteList();
//        $this->group_black_list = $this->_getGroupBlackList();
//        $this->qq_white_list = $this->_getQQWhiteList();
//        $this->qq_black_list = $this->_getGroupBlackList();
//        $this->keyword = $this->_getKeyWord();
        $this->CoolQ = new CoolQSDK($this->host, $this->port, $this->token);
    }


    /**
     * @return Mysql|null
     */
    public function getMysql()
    {
        return $this->Mysql;
    }

    /**
     * @param Mysql|null $Mysql
     */
    public function setMysql($Mysql)
    {
        $this->Mysql = $Mysql;
    }

    /**
     * @return mixed
     */
    public function getQQ()
    {
        return $this->QQ;
    }


    /**
     * @return mixed
     */
    public function getCoolQ()
    {
        return $this->CoolQ;
    }


    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host)
    {
        $this->host = $host;
        return self::setRobotValue('host',$host);
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port)
    {
        $this->port = $port;
        return self::setRobotValue('port',$port);

    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
        return self::setRobotValue('token',$token);
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return self::setRobotValue('status',$status);
    }

    /**
     * @return mixed
     */
    public function getManager()
    {
        $this->manager = $this->_getManager();
        return $this->manager;
    }

    /**
     * @param mixed $manager
     */
    public function setManager()
    {

    }

    /**
     * @return mixed
     */
    public function getGroupWhiteList()
    {
        unset($this->group_white_list);
        foreach ($this->_getGroupWhiteList() as $value){
            $this->group_white_list[] = $value['group_id'];
        }
        return $this->group_white_list;
    }

    /**
     * @param mixed $group_white_list
     */
    public function setGroupWhiteList()
    {

    }

    /**
     * @return mixed
     */
    public function getGroupBlackList()
    {
        unset($this->group_black_list);
        foreach ($this->_getGroupBlackList() as $value){
            $this->group_black_list[] = $value['group_id'];
        }
        return $this->group_black_list;
    }

    /**
     * @param mixed $group_black_list
     */
    public function setGroupBlackList()
    {
    }

    /**
     * @return mixed
     */
    public function getQqWhiteList()
    {
        unset($this->qq_white_list);
        foreach ($this->_getQQWhiteList() as $value){
            $this->qq_white_list[] = $value['user_id'];
        }
        return $this->qq_white_list;
    }

    /**
     * @param mixed $qq_white_list
     */
    public function setQqWhiteList()
    {
    }

    /**
     * @return mixed
     */
    public function getQqBlackList()
    {
        unset($this->qq_black_list);
        foreach ($this->_getQQBlackList() as $value){
            $this->qq_black_list[] = $value['user_id'];
        }
        return $this->qq_black_list;
    }

    /**
     * @param mixed $qq_black_list
     */
    public function setQqBlackList()
    {
    }

    /**
     * @return mixed
     */
    public function getKeyword()
    {
        unset($this->keyword);
        foreach ($this->_getKeyWord() as $value){
            $this->keyword[] = $value['keyword'];
        }
        return $this->keyword;
    }

    /**
     * @param mixed $keyword
     */
    public function setKeyword()
    {
    }

    /**
     * @return mixed
     */
    public function getIsGroupWhiteList()
    {
        return $this->is_group_white_list;
    }

    /**
     * @param mixed $is_group_white_list
     */
    public function setIsGroupWhiteList($is_group_white_list)
    {
        $this->is_group_white_list = $is_group_white_list;
        return self::setRobotValue('is_group_white_list',$is_group_white_list);
    }

    /**
     * @return mixed
     */
    public function getIsGroupBlackList()
    {
        return $this->is_group_black_list;
    }

    /**
     * @param mixed $is_group_black_list
     */
    public function setIsGroupBlackList($is_group_black_list)
    {
        $this->is_group_black_list = $is_group_black_list;
        return self::setRobotValue('is_group_black_list',$is_group_black_list);
    }

    /**
     * @return mixed
     */
    public function getIsQqWhiteList()
    {
        return $this->is_qq_white_list;
    }

    /**
     * @param mixed $is_qq_white_list
     */
    public function setIsQqWhiteList($is_qq_white_list)
    {
        $this->is_qq_white_list = $is_qq_white_list;
        return self::setRobotValue('is_qq_white_list',$is_qq_white_list);
    }

    /**
     * @return mixed
     */
    public function getIsQqBlackList()
    {
        return $this->is_qq_black_list;
    }

    /**
     * @param mixed $is_qq_black_list
     */
    public function setIsQqBlackList($is_qq_black_list)
    {
        $this->is_qq_black_list = $is_qq_black_list;
        return self::setRobotValue('is_qq_black_list',$is_qq_black_list);

    }

    /**
     * @return mixed
     */
    public function getIsAt()
    {
        return $this->is_at;
    }

    /**
     * @param mixed $is_at
     */
    public function setIsAt($is_at)
    {
        $this->is_at = $is_at;
        return self::setRobotValue('is_at',$is_at);

    }

    /**
     * @return mixed
     */
    public function getIsKeyword()
    {
        return $this->is_keyword;
    }

    /**
     * @param mixed $is_keyword
     */
    public function setIsKeyword()
    {
    }

    /**
     * @return mixed
     */
    public function getIsFollow()
    {
        return $this->is_follow;
    }

    /**
     * @param mixed $is_follow
     */
    public function setIsFollow($is_follow)
    {
        $this->is_follow = $is_follow;
        return self::setRobotValue('is_follow',$is_follow);
    }

    /**
     * @return mixed
     */
    public function getIsOnFriend()
    {
        return $this->is_on_friend;
    }

    /**
     * @param mixed $is_on_friend
     */
    public function setIsOnFriend($is_on_friend)
    {
        $this->is_on_friend = $is_on_friend;
        return self::setRobotValue('is_on_friend',$is_on_friend);

    }

    /**
     * @return mixed
     */
    public function getIsOnGroup()
    {
        return $this->is_on_group;
    }

    /**
     * @param mixed $is_on_group
     */
    public function setIsOnGroup($is_on_group)
    {
        $this->is_on_group = $is_on_group;
        return self::setRobotValue('is_on_group',$is_on_group);
    }

    /**
     * @return mixed
     */
    public function getIsOnDiscuss()
    {
        return $this->is_on_discuss;
    }

    /**
     * @param mixed $is_on_discuss
     */
    public function setIsOnDiscuss($is_on_discuss)
    {
        $this->is_on_discuss = $is_on_discuss;
        return self::setRobotValue('is_on_discuss',$is_on_discuss);
    }

    /**
     * @return mixed
     */
    public function getIsAgreeFriend()
    {
        return $this->is_agree_friend;
    }

    /**
     * @param mixed $is_agree_friend
     */
    public function setIsAgreeFriend($is_agree_friend)
    {
        $this->is_agree_friend = $is_agree_friend;
        return self::setRobotValue('is_agree_friend',$is_agree_friend);
    }

    /**
     * @return mixed
     */
    public function getIsAgreeGroup()
    {
        return $this->is_agree_group;
    }

    /**
     * @param mixed $is_agree_group
     */
    public function setIsAgreeGroup($is_agree_group)
    {
        $this->is_agree_group = $is_agree_group;
        return self::setRobotValue('is_agree_group',$is_agree_group);
    }

    /**
     * @return mixed
     */
    public function getIsOnPlugin()
    {
        return $this->is_on_plugin;
    }

    /**
     * @param mixed $is_on_plugin
     */
    public function setIsOnPlugin($is_on_plugin)
    {
        $this->is_on_plugin = $is_on_plugin;
        return self::setRobotValue('is_on_plugin',$is_on_plugin);
    }

    /**
     * @return mixed
     */
    public function getGroupRefuseReason()
    {
        return $this->group_refuse_reason;
    }

    /**
     * @param mixed $group_refuse_reason
     */
    public function setGroupRefuseReason($group_refuse_reason)
    {
        $this->group_refuse_reason = $group_refuse_reason;
        return self::setRobotValue('group_refuse_reason',$group_refuse_reason);
    }

    /**
     * @return mixed
     */
    public function getQqRefuseReason()
    {
        return $this->qq_refuse_reason;
    }

    /**
     * @param mixed $qq_refuse_reason
     */
    public function setQqRefuseReason($qq_refuse_reason)
    {
        $this->qq_refuse_reason = $qq_refuse_reason;
        return self::setRobotValue('qq_refuse_reason',$qq_refuse_reason);
    }

    /**
     * @return mixed
     */
    public function getIsReplyAt()
    {
        return $this->is_reply_at;
    }

    /**
     * @param mixed $is_reply_at
     */
    public function setIsReplyAt($is_reply_at)
    {
        $this->is_reply_at = $is_reply_at;
        return self::setRobotValue('is_reply_at',$is_reply_at);
    }

    public function initGroupList(){

    }

    private function _getManager()
    {
        $sql = "select * from coolq_robot_manager WHERE qq = $this->QQ";
        $res = $this->Mysql->query($sql);
        return $this->Mysql->getAll($res);
    }

    private function _getGroupWhiteList()
    {
        $sql = "select * from coolq_group WHERE qq = $this->QQ AND  status = 1";
        $res = $this->Mysql->query($sql);
        return $this->Mysql->getAll($res);
    }

    private function _getGroupBlackList()
    {
        $sql = "select * from coolq_group WHERE qq = $this->QQ AND  status = -1";
        $res = $this->Mysql->query($sql);
        return $this->Mysql->getAll($res);
    }

    private function _getQQWhiteList()
    {
        $sql = "select * from coolq_friend WHERE qq = $this->QQ AND  status = 1";
        $res = $this->Mysql->query($sql);
        return $this->Mysql->getAll($res);
    }

    private function _getQQBlackList()
    {
        $sql = "select * from coolq_friend WHERE qq = $this->QQ AND  status = -1";
        $res = $this->Mysql->query($sql);
        return $this->Mysql->getAll($res);
    }

    private function _getKeyWord()
    {
        $sql = "select * from coolq_keyword WHERE qq = $this->QQ";
        $res = $this->Mysql->query($sql);
        return $this->Mysql->getAll($res);
    }

    public static function getInstance($QQ)
    {
        // 检查对象是否已经存在，不存在则实例化后保存到$instance属性
        if (self::$instance == null) {
            self::$instance = new self($QQ);
        }
        return self::$instance;
    }

    private function setRobotValue($key,$value){
        $sql = "update coolq_robot set $key =  '$value' where qq = '$this->QQ' ";
        return $this->Mysql->query($sql);
    }




    public function setIsSend($bool){
        $this->isSend = $bool;
    }










    /**
     * CoolQ
     */


    public function getLoginInfo()
    {
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
    public function sendPrivateMsg($user_id, $message, $is_raw = false)
    {
         switch ($this->isSend){
             case OFF:
                 break;
             case ON:
                 TimeTool::StartTime();
                 $this->CoolQ->sendPrivateMsg($user_id, $message, true);
                 $dtime = TimeTool::EndTime()['time'];
                 Log::Info("to:$user_id  $message \n 上报耗时:$dtime ",$message,Plugin::getPluginName(),$this->getQQ());
                 break;
             case FOLLOW:
                 TimeTool::StartTime();
                 $this->CoolQ->sendPrivateMsg($user_id, CQ::deCodeHtml($message), false);
                 $dtime = TimeTool::EndTime()['time'];
                 Log::Info("to:$user_id  $message \n 上报耗时:$dtime ",$message,Plugin::getPluginName(),$this->getQQ());
                 break;
             case REPLYAT:
                 break;
         }
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
    public function sendGroupMsg($group_id, $message, $user_id = '' ,$is_raw = false)
    {
        switch ($this->isSend) {
            case OFF:
                break;
            case ON:
                TimeTool::StartTime();
                $dtime = TimeTool::EndTime()['time'];
                $this->CoolQ->sendGroupMsg($group_id, CQ::deCodeHtml($message), true);
                Log::Info("to:$group_id  $message \n 上报耗时:$dtime ", $message, Plugin::getPluginName(), $this->getQQ());
                break;
            case FOLLOW:
                TimeTool::StartTime();
                $this->CoolQ->sendGroupMsg($group_id, CQ::filterCQAt(CQ::deCodeHtml($message)), $is_raw ,true);
                $dtime = TimeTool::EndTime()['time'];
                Log::Info("to:$group_id  $message \n 上报耗时:$dtime ", $message, Plugin::getPluginName(), $this->getQQ());
                break;
            case REPLYAT:
                TimeTool::StartTime();
                $data[0]['type'] = 'at';
                $data[0]['data'] = array(
                    'qq'=>"$user_id"
                );
                $data[1]['type'] = 'text';
                $data[1]['data'] = array(
                    'text'=>CQ::filterCQAt(CQ::deCodeHtml($message))
                );
                $this->CoolQ->sendGroupMsg($group_id, $data, $is_raw,true);
                $dtime = TimeTool::EndTime()['time'];
                Log::Info("to:$group_id  $message \n 上报耗时:$dtime ", $message, Plugin::getPluginName(), $this->getQQ());
                break;
            case REPLYATFOLLOW:
                TimeTool::StartTime();
                $this->CoolQ->sendGroupMsg($group_id, CQ::enAtCode($user_id) . CQ::filterCQAt(CQ::deCodeHtml($message)), false);
                $dtime = TimeTool::EndTime()['time'];
                Log::Info("to:$group_id  $message \n 上报耗时:$dtime ", $message, Plugin::getPluginName(), $this->getQQ());
                break;
        }
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
    }

    /**
     * /get_group_list  获取群列表
     *   参数
     *        字段名    数据类型    默认值    说明
     * @return mixed|string
     */
    public function getGroupList()
    {
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
    }

    /**
     * /get_cookies 获取 Cookies
     * @return mixed|string
     */
    public function getCookies()
    {
    }

    /**
     * /get_csrf_token 获取 CSRF Token
     * @return mixed|string
     */
    public function getCsrfToken()
    {
    }



    /**
     * Puglin
     */

    public function getRobotPluginOrders()
    {
        $sql = "SELECT coolq_plugin.name,coolq_plugin_order.plugin_id,coolq_plugin_order.order_name,coolq_plugin.priority,coolq_plugin.status,coolq_plugin.plugin_class FROM coolq_plugin_order,coolq_plugin WHERE  coolq_plugin_order.plugin_id = coolq_plugin.id  AND  coolq_plugin.status = 1 ORDER BY  coolq_plugin.priority DESC ";
        $rs = $this->Mysql->query($sql);
        $array = array();
        while ($r = $this->Mysql->getOne($rs)) {
            $array[] = $r;
        }
        return $array;
    }


    public static function runPlugin($plugin_class_name)
    {
        $Plugin = null;
        global $Robot;
//        echo $plugin_class_name . "<br>";
        include_once  "Plugin/$plugin_class_name/" . $plugin_class_name . ".php";
        Plugin::getPluginName($plugin_class_name);
        eval("@\$Plugin = new " . $plugin_class_name . "();");
        if ($Plugin == null)return null;
        @$Plugin->Start();
        return @$Plugin;
    }

}