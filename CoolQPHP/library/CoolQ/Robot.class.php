<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/5 0005
 * Time: 14:39
 */
class Robot extends CoolQ
{

    public $DB;
    public $token;
    public $snoopy;
    public $path;
    public $QQ;
    public $manager;
    public $group_white_list;
    public $group_black_list;
    public $qq_white_list;
    public $qq_black_list;
    public $is_qq_white_list;
    public $is_qq_black_list;
    public $is_group_white_list;
    public $is_group_black_list;
    public $is_at;
    public $is_keyword;
    public $is_follow;
    public $keyword;
    public $is_on_friend;
    public $is_on_group;
    public $is_on_discuss;
    public $is_agree_friend;
    public $is_agree_group;
    public $status;

    function __construct($QQ, $token = "")
    {
        $this->token = $token;
        $this->QQ = $QQ;
        $this->snoopy = new Snoopy();
        $this->snoopy->headers['Authorization'] = 'token ' . $this->token;
        $this->path = PATH . ':' . PORT . '/';
        $this->DB = new DB(dbHost, dbUser, dbPassword, dbTable, dbport);
        $res = $this->DB->query("select * from coolq_robot where qq = '$QQ'");
        if (mysqli_num_rows($res) <= 0) {
            $time = date("Y-m-d H:i:s", time());
            $manager[] = MANAGERQQ;
            $this->manager = $manager;
            $manager = json_encode($manager, JSON_UNESCAPED_UNICODE);
            $this->DB->query("insert into coolq_robot(qq,time,manager,group_white_list, group_black_list, qq_white_list, qq_black_list,keyword) values ('$QQ','$time','$manager','[]','[]','[]','[]','[]') ");
            $this->qq_black_list = array();
            $this->qq_white_list = array();
            $this->group_white_list = array();
            $this->group_black_list = array();
            $this->is_at = 0;
            $this->is_keyword = 0;
            $this->is_group_black_list = 0;
            $this->is_group_white_list = 0;
            $this->is_qq_black_list = 0;
            $this->is_qq_white_list = 0;
            $this->is_at = 0;
            $this->is_on_friend = 0;
            $this->is_on_group = 0;
            $this->is_on_discuss = 0;
            $this->keyword = array();
            $this->is_agree_friend = 0;
            $this->is_agree_group = 0;
            $this->is_follow = 0;
            $this->status = 0;
        } else {
            $rs = $this->DB->getone($res);
            $this->manager = json_decode($rs['manager'], true);
            $this->qq_black_list = json_decode($rs['qq_black_list'], true);
            $this->qq_white_list = json_decode($rs['qq_white_list'], true);
            $this->group_white_list = json_decode($rs['group_white_list'], true);
            $this->group_black_list = json_decode($rs['group_black_list'], true);
            $this->is_group_black_list = $rs['is_group_black_list'];
            $this->is_group_white_list = $rs['is_group_white_list'];
            $this->is_qq_black_list = $rs['is_qq_black_list'];
            $this->is_qq_white_list = $rs['is_qq_white_list'];
            $this->is_at = $rs['is_at'];
            $this->is_keyword = $rs['is_keyword'];
            $this->is_on_friend = $rs['is_on_friend'];
            $this->is_on_group = $rs['is_on_group'];
            $this->is_on_discuss = $rs['is_on_discuss'];
            $this->is_agree_friend = $rs['is_agree_friend'];
            $this->is_agree_group = $rs['is_agree_group'];
            $this->is_follow = $rs['is_follow'];
            $this->keyword = json_decode($rs['keyword'], true);
            $this->status = $rs['status'];
        }
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


    public function getPluginOrders()
    {
        $sql = "SELECT coolq_plugin_list.name,coolq_plugin_orders.plugin_id,coolq_plugin_orders.order_name,coolq_plugin_list.priority,coolq_plugin_list.status,coolq_plugin_list.plugin_class FROM coolq_plugin_orders,coolq_plugin_list WHERE  coolq_plugin_orders.	plugin_id = coolq_plugin_list.id   ORDER BY  coolq_plugin_list.priority ASC";
        $rs = $this->DB->query($sql);
        $array = array();
        while ($r = $this->DB->getone($rs)) {
            $array[] = $r;
        }
        return $array;
    }

    public function getManage()
    {
        return $this->manager;
    }

    /**
     * @param mixed $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
        $manager = json_encode($manager, JSON_UNESCAPED_UNICODE);
        $this->DB->query("UPDATE coolq_robot SET manager = '$manager' WHERE qq = '$this->QQ'");
    }

    /**
     * @param mixed $group_white_list
     */
    public function setGroupWhiteList($group_white_list)
    {
        $this->group_white_list = $group_white_list;
        $group_white_list = json_encode($group_white_list, JSON_UNESCAPED_UNICODE);
        $this->DB->query("UPDATE coolq_robot SET group_white_list = '$group_white_list' WHERE qq = '$this->QQ'");
    }

    /**
     * @param mixed $group_black_list
     */
    public function setGroupBlackList($group_black_list)
    {
        $this->group_black_list = $group_black_list;
        $group_black_list = json_encode($group_black_list, JSON_UNESCAPED_UNICODE);
        $this->DB->query("UPDATE coolq_robot SET group_black_list = '$group_black_list' WHERE qq = '$this->QQ'");

    }

    /**
     * @param mixed $qq_white_list
     */
    public function setQqWhiteList($qq_white_list)
    {
        $this->qq_white_list = $qq_white_list;
        $qq_white_list = json_encode($qq_white_list, JSON_UNESCAPED_UNICODE);
        $this->DB->query("UPDATE coolq_robot SET qq_white_list = '$qq_white_list' WHERE qq = '$this->QQ'");

    }

    /**
     * @param mixed $qq_black_list
     */
    public function setQqBlackList($qq_black_list)
    {
        $this->qq_black_list = $qq_black_list;
        $qq_black_list = json_encode($qq_black_list, JSON_UNESCAPED_UNICODE);
        $this->DB->query("UPDATE coolq_robot SET qq_black_list = '$qq_black_list' WHERE qq = '$this->QQ'");

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
        $this->DB->query("UPDATE coolq_robot SET is_qq_white_list = '$is_qq_white_list' WHERE qq = '$this->QQ'");

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
        $this->DB->query("UPDATE coolq_robot SET is_qq_black_list = '$is_qq_black_list' WHERE qq = '$this->QQ'");

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
        $this->DB->query("UPDATE coolq_robot SET is_group_white_list = '$is_group_white_list' WHERE qq = '$this->QQ'");

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
        $this->DB->query("UPDATE coolq_robot SET is_group_black_list = '$is_group_black_list' WHERE qq = '$this->QQ'");

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
        $this->DB->query("UPDATE coolq_robot SET is_at = '$is_at' WHERE qq = '$this->QQ'");

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
        $this->DB->query("UPDATE coolq_robot SET is_keyword = '$is_keyword' WHERE qq = '$this->QQ'");

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
        $this->DB->query("UPDATE coolq_robot SET is_on_friend = '$is_on_friend' WHERE qq = '$this->QQ'");
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
        $this->DB->query("UPDATE coolq_robot SET is_on_group = 'is_on_group' WHERE qq = '$this->QQ'");
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
        $this->DB->query("UPDATE coolq_robot SET is_on_discuss = '$is_on_discuss' WHERE qq = '$this->QQ'");
    }


    /**
     * @param mixed $keyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
        $keyword = json_encode($keyword, JSON_UNESCAPED_UNICODE);
        $this->DB->query("UPDATE coolq_robot SET keyword = '$keyword' WHERE qq = '$this->QQ'");

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
        $this->DB->query("UPDATE coolq_robot SET is_agree_friend = '$is_agree_friend' WHERE qq = '$this->QQ'");

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
        $this->DB->query("UPDATE coolq_robot SET is_agree_group = '$is_agree_group' WHERE qq = '$this->QQ'");

    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        if ($status) {
            $status = 1;
        } else {
            $status = 0;
        }
        $this->status = $status;
        $this->DB->query("UPDATE coolq_robot SET status = '$status' WHERE qq = '$this->QQ'");
    }


}