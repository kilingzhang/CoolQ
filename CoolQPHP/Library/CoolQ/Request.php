<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/7/4
 * Time: 16:15
 */

namespace CoolQ;


class Request
{
    static public function get($param = null,$default = '' ,$filter = true){
        $get = null;
        $param == null &&  $get = $_GET;
        if(is_array($param) && count($param) > 0){
           foreach ($param as $item){
               $get[$item] = isset($_GET[$item]) ? $_GET[$item] : $default;
           }
        }else if($param != null){
            $get[$param] = isset($_GET[$param]) ? $_GET[$param] : $default;
        }
        if($filter  == true){
            foreach ($get as $key => $value){
                $get[$key] = htmlspecialchars($value);
            }
        }
        return $get;
    }


    static public function post($param = null,$default = '' ,$filter = true){
        $get = null;
        $param == null &&  $get = $_POST;
        if(is_array($param) && count($param) > 0){
            foreach ($param as $item){
                $get[$item] = isset($_POST[$item]) ? $_POST[$item] : $default;
            }
        }else if($param != null){
            $get[$param] = isset($_POST[$param]) ? $_POST[$param] : $default;
        }
        if($filter  == true){
            foreach ($get as $key => $value){
                $get[$key] = htmlspecialchars($value);
            }
        }
        return $get;
    }


    static public function put($param = null,$json = true ,$filter = true){
        $get = null;
        $content = file_get_contents('php://input');
        if($json == true){
            $get = json_decode($content,true);
        }
        return $get;
    }

    static public function server($param = null,$default = '' ,$filter = true){
        $get = null;
        $param == null &&  $get = $_SERVER;
        if(is_array($param) && count($param) > 0){
            foreach ($param as $item){
                $get[$item] = isset($_SERVER[$item]) ? $_SERVER[$item] : $default;
            }
        }else if($param != null){
            $get[$param] = isset($_SERVER[$param]) ? $_SERVER[$param] : $default;
        }
        if($filter  == true){
            foreach ($get as $key => $value){
                $get[$key] = htmlspecialchars($value);
            }
        }
        return $get;
    }

    static public function contentType(){
        $contentType = self::server('CONTENT_TYPE');
        if ($contentType) {
            $type = $contentType['CONTENT_TYPE'];
            return trim($type);
        }
        return '';
    }

}