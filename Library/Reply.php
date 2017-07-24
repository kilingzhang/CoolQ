<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/13
 * Time: 17:08
 */

namespace Library;


class Reply
{

    /**
     * 设置接口提示信息
     * @param $retVal
     * @return mixed
     */
    public static function setMsg($retVal)
    {
        $retValToMsg = array(
            0 => '成功',
            1 => '用户名或密码错误',
            2 => '超时',
            3 => '网络故障',
            4 => '未知错误',
            5 => '验证码错误',
            6 => '成功(未修改密码)',
            7 => '学校服务器500 请重新登陆',
            8 => '数据获取失败 请重试',
            65535 => '缺失参数',
        );
        return $retValToMsg[$retVal];
    }

    public static function Reply($param = array(), $code = 0, $msg = '')
    {

        $jsonArray = array(
            'code' => $code,
            'msg' => self::setMsg($code),
            'data' => $param,
        );
        if ($msg != '') {
            $jsonArray['msg'] = $msg;
        }
        $jsonData = json_encode($jsonArray, JSON_UNESCAPED_UNICODE);
        return $jsonData;
    }

    public static function EchoReply($param = array(), $code = 0, $msg = '')
    {

        $jsonArray = array(
            'code' => $code,
            'msg' => self::setMsg($code),
            'data' => $param,
        );
        if ($msg != '') {
            $jsonArray['msg'] = $msg;
        }
        $jsonData = json_encode($jsonArray, JSON_UNESCAPED_UNICODE);
        echo $jsonData;
    }


}