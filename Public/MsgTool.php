<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/6 0006
 * Time: 00:13
 */
class MsgTool
{
    public static function in_Array($str, $array)
    {
        $array = json_encode($array, JSON_UNESCAPED_UNICODE);
        $pro = explode($str, $array);
        if (count($pro) >= 2) {
            return true;
        } else {
            return false;
        }
    }

    public static function in_String($str, $String)
    {
        $pro = explode($str, $String);
        if (count($pro) >= 2) {
            return true;
        } else {
            return false;
        }
    }

    public static function Array_In_String($str, $array)
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

}