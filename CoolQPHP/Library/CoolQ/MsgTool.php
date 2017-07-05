<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/6
 * Time: 1:59
 */

namespace CoolQ;


class MsgTool
{
    public static function inArray($str, $array)
    {
        $array = json_encode($array, JSON_UNESCAPED_UNICODE);
        $pro = explode($str, $array);
        if (count($pro) >= 2) {
            return true;
        } else {
            return false;
        }
    }
    public static function inString($str, $String)
    {
        $pro = explode($str, $String);
        if (count($pro) >= 2) {
            return true;
        } else {
            return false;
        }
    }
    public static function arrayItemIsInString($str, $array)
    {
        foreach ($array as $item) {
            $pro = explode($item, $str);
            if (count($pro) >= 2) {
                return true;
            } else {
                continue;
            }
        }
        return false;
    }

    public static function filterCQAt($string){
        return preg_replace('/\[CQ:at,qq=\d+\]/','',$string);
    }

    public static function deCodeHtml($message)
    {
        $message = preg_replace("/&amp;/", "&", $message);
        $message = preg_replace("/&#91;/", "[", $message);
        $message = preg_replace("/&#93;/", "]", $message);
        return $message;
    }
}