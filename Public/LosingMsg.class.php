<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/5 0005
 * Time: 13:55
 */
class LosingMsg
{
    public $code;
    public $data;
    public $msg;
    public $con;
    public $result;

    public function __construct($msg = '')
    {
        $this->con = $msg;
        $this->result = $this->init();
    }

    public function is_strArrayPreg($msg, $array = array(), $type = '|')
    {
        switch ($type) {
            case '|':
                $n = count($array);
                if ($n == 0) {
                    return FALSE;
                } else {
                    foreach ($array as $k => $v) {
                        $pre = "/($v)/";
                        preg_match_all($pre, $msg, $rs);
                        // show($rs,1);
                        if (isset($rs[1]) && $rs[1] != '') {
                            return TRUE;
                        }

                    }
                    return FALSE;
                }
                break;

            case '&':
                $n = count($array);
                if ($n == 0) {
                    return FALSE;
                } else {
                    foreach ($array as $k => $v) {
                        $pre = "/($v)/";
                        preg_match_all($pre, $msg, $rs);
                        if (!isset($rs[1]) || $rs[1] === '') {
                            return FALSE;
                        }

                    }
                    return TRUE;
                }
                break;

            default:
                # code...
                break;
        }
    }

    public function is_preg($array = array())
    {
        // show($array,1);
        if (isset($array[1]) && $array[1] != null) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    public function init()
    {
        $array_lose = array(
            '失', '捡', '丢', '寻'
        );
        $array_goods = array(
            '校园卡', '身份证', '手机', '电话', '伞', '钱包', '书', '资料'
        );
        $array_conect = array(
            'QQ', 'qq', '电话', '手机号'
        );
        preg_match_all('/(1(3[0-9]|4[57]|5[0-35-9]|7[01678]|8[0-9])\d{8})/', $this->con, $tele);
        preg_match_all('/(\d{5,10})/', $this->con, $QQ);

        preg_match_all('/(201\d{7})\D/', $this->con, $Student_sid);
        preg_match_all('/(19\d{6})\D/', $this->con, $Teacher_sid_1);
        preg_match_all('/(20\d{6})\D/', $this->con, $Teacher_sid_2);

        $QQ = $this->is_preg($QQ);
        $tele = $this->is_preg($tele);
        $Student_sid = $this->is_preg($Student_sid);
        $Teacher_sid_1 = $this->is_preg($Teacher_sid_1);
        $Teacher_sid_2 = $this->is_preg($Teacher_sid_2);

        $lose = $this->is_strArrayPreg($this->con, $array_lose);
        $goods = $this->is_strArrayPreg($this->con, $array_goods);
        $conect = $this->is_strArrayPreg($this->con, $array_conect);

        if ($lose) {
            if ($goods || $Student_sid || $Teacher_sid_1 || $Teacher_sid_2) {

                if ($QQ || $tele) {
                    return TRUE;
                } else {
                    return FALSE;
                }

            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }

    }


    public function getData()
    {
        if ($this->result) {
            $this->code = 1;
            $this->msg = 'is Losing';
        } else {
            $this->code = 0;
            $this->msg = 'not Losing';
        }
        $jsonArray = array(
            'code' => $this->code,
            'msg' => $this->msg,
            'data' => $this->con,
        );
        $jsonData = json_encode($jsonArray, JSON_UNESCAPED_UNICODE);
        $jsonData = urldecode($jsonData);

        return $jsonData;
    }

    public function isInsert()
    {
        if ($this->result) {
            $this->code = 1;
            $this->msg = 'is Losing';
            return true;
        } else {
            $this->code = 0;
            $this->msg = 'not Losing';
            return false;
        }
    }


}