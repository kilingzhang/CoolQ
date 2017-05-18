<?php

class CoolQ
{

    public $token;
    public $snoopy;
    public $path;

    function __construct()
    {
        $this->token = TOKEN;
        $this->snoopy = new Snoopy();
        $this->snoopy->headers['Authorization'] = 'token ' . $this->token;
        $this->path = PATH . ':' . PORT . '/';
    }

    /**
     * @return string json
     */
    public function getLoginInfo()
    {
        $url = $this->path . "get_login_info";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public static function deCodeHtml($message){
        $message = preg_replace("/&amp;/","&",$message);
        $message = preg_replace("/&#91;/","[",$message);
        $message = preg_replace("/&#93;/","]",$message);
        return $message;
    }

    public function sendPrivateMsg($user_id, $message, $is_raw = 'false')
    {
        $message = urlencode($message);
        $url = $this->path . "send_private_msg?user_id=$user_id&message=$message&is_raw=$is_raw";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function sendGroupMsg($group_id, $message, $is_raw = 'false')
    {
        $message = urlencode($message);
        $url = $this->path . "send_group_msg?group_id=$group_id&message=$message&is_raw=$is_raw";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function sendDiscussMsg($discuss_id, $message, $is_raw = 'false')
    {
        $message = urlencode($message);
        $url = $this->path . "send_discuss_msg?discuss_id=$discuss_id&message=$message&is_raw=$is_raw";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function sendLike($user_id)
    {
        $url = $this->path . "send_like?user_id=$user_id";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function setGroupKick($group_id, $user_id, $reject_add_request = 'false')
    {
        $url = $this->path . "set_group_kick?group_id=$group_id&user_id=$user_id&reject_add_request=$reject_add_request";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function setGroupBan($group_id, $user_id, $duration = 30)
    {
        $duration = $duration * 60;
        $url = $this->path . "set_group_ban?group_id=$group_id&user_id=$user_id&duration=$duration";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function setGroupWholeBan($group_id, $enable = 'true')
    {
        $url = $this->path . "set_group_whole_ban?group_id=$group_id&enable=$enable";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function setGroupAnonymousBan($group_id, $flag, $duration = 30)
    {
        $url = $this->path . "set_group_anonymous_ban?group_id=$group_id&flag=$flag&duration=$duration";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function setGroupAdmin($group_id, $user_id, $enable = 'true')
    {
        $url = $this->path . "set_group_admin?group_id=$group_id&user_id=$user_id&enable=$enable";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function setGroupAnonymous($group_id, $enable = 'true')
    {
        $url = $this->path . "set_group_anonymous?group_id=$group_id&enable=$enable";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function setGroupSpecialTitle($group_id, $user_id, $special_title = "", $duration = -1)
    {
        $url = $this->path . "set_group_special_title?group_id=$group_id&user_id=$user_id&special_title=$special_title&duration=$duration";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function setGroupCard($group_id, $user_id, $card)
    {
        $url = $this->path . "set_group_card?group_id=$group_id&user_id=$user_id&card=$card";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function setGroupLeave($group_id, $is_dismiss = 'false')
    {
        $url = $this->path . "set_group_leave?group_id=$group_id&is_dismiss=$is_dismiss";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function setDiscussLeave($discuss_id)
    {
        $url = $this->path . "set_discuss_leave?discuss_id=$discuss_id";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function setFriendAddRequest($flag, $approve = 'true', $remark = "")
    {
        $url = $this->path . "set_friend_add_request?flag=$flag&approve=$approve&remark=$remark";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function setGroupAddRequest($flag, $type, $approve = 'true', $remark = "")
    {
        $url = $this->path . "set_group_add_request?flag=$flag&type=$type&approve=$approve&remark=$remark";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function getGroupMemberInfo($group_id, $user_id, $no_cache = 'false')
    {
        $url = $this->path . "get_group_member_info?group_id=$group_id&user_id=$user_id&no_cache=$no_cache";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function getStrangerInfo($user_id, $no_cache = 'false')
    {
        $url = $this->path . "get_stranger_info?user_id=$user_id&no_cache=$no_cache";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function getCookies()
    {
        $url = $this->path . "get_cookies";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

    public function getCsrfToken()
    {
        $url = $this->path . "get_csrf_token";
        $res = $this->snoopy->fetch($url)->results;
        return $res;
    }

}