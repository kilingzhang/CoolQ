<?php

include_once "Public/Config.php";
include_once "Public/DB.class.php";
include_once "Public/Snoopy.class.php";
include_once "Public/CoolQ.class.php";
include_once "Public/Robot.class.php";
include_once "Public/Log.class.php";
include_once "Public/Plugin.class.php";
include_once "Public/MsgTool.php";
include_once "Public/CQ.php";

$json = file_get_contents("php://input") ? file_get_contents("php://input") : '{"post_type":"message","message_type":"group","time":1494008393,"group_id":194233857,"user_id":1353693508,"anonymous":"","sub_type":"friend","anonymous_flag":"","message":"[CQ:at,qq=2093208406] [CQ:image,file=765F30418D4A8BD543FB94CBADCBB7D4.jpg,url=http://gchat.qpic.cn/gchatpic_new/1353693508/2184233857-2553815795-765F30418D4A8BD543FB94CBADCBB7D4/0]"}';
$data = json_decode($json, true);
/*
 * 初始化机器人
 */
$Robot = new Robot(QQ, TOKEN);

/*
 * 获得插件指令
 */
$PluginOrders = $Robot->getPluginOrders();
$Plugin = null;
$post_type = $data['post_type'];
if (!$Robot->status) {
    exit('{"block": true}');
}
switch ($post_type) {

    case 'message':
        $message_type = $data['message_type'];

        /*
         * 开启跟随模式
         */
        if ($Robot->is_follow == true) {
            switch ($message_type) {
                case "private":
                    $user_id = $data['user_id'];
                    $message = $data['message'];
                    $Robot->sendPrivateMsg($user_id, CoolQ::deCodeHtml($message));
                    break;
                case "group":
                    $user_id = $data['user_id'];
                    $message = $data['message'];
                    $group_id = $data['group_id'];
                    $Robot->sendGroupMsg($group_id, CoolQ::deCodeHtml($message));
                    break;
                case "discuss":
                    $discuss_id = $data['discuss_id'];
                    $Robot->sendDiscussMsg($discuss_id, CoolQ::deCodeHtml($message));
                    break;
            }
            return;
        }


        /*
         * 消息 Log日志
         */
        switch ($message_type) {
            case "private":
                $sub_type = $data['sub_type'];
                Log::InsertMsgLog($data['message'], $data['user_id'], "", "", 0, "", $message_type, $sub_type);
                /*
                 * 验证是否处理消息
                 */
                $user_id = $data['user_id'];
                $message = $data['message'];
                /*
                * 判断是否开启私聊消息回复
                */
                if (!$Robot->is_on_friend) {
                    exit('{"block": true}');
                }
                if (!$Robot->is_qq_black_list && !$Robot->is_qq_white_list) {

                    if ($Robot->is_keyword && MsgTool::Array_In_String($message, $Robot->keyword)) {
                        /*
                         * 将消息下发到插件
                         */
                        $Plugin = runOders($PluginOrders, $data, $Robot);
                    } elseif (!$Robot->is_keyword) {
                        /*
                         * 无任何限制
                         */
                        $Plugin = runOders($PluginOrders, $data, $Robot);
                    }

                } else if ($Robot->is_qq_white_list) {
                    if (MsgTool::in_Array($user_id, $Robot->qq_white_list)) {
                        if ($Robot->is_keyword && MsgTool::Array_In_String($message, $Robot->keyword)) {
                            /*
                             * 将消息下发到插件
                             */
                            $Plugin = runOders($PluginOrders, $data, $Robot);
                        } elseif (!$Robot->is_keyword) {
                            /*
                             * 无任何限制
                             */
                            $Plugin = runOders($PluginOrders, $data, $Robot);
                        }
                    }
                } else if ($Robot->is_qq_black_list) {
                    if (!MsgTool::in_Array($user_id, $Robot->qq_black_list)) {
                        if ($Robot->is_keyword && MsgTool::Array_In_String($message, $Robot->keyword)) {
                            /*
                             * 将消息下发到插件
                             */
                            $Plugin = runOders($PluginOrders, $data, $Robot);
                        } elseif (!$Robot->is_keyword) {
                            /*
                             * 无任何限制
                             */
                            $Plugin = runOders($PluginOrders, $data, $Robot);
                        }
                    }
                }

                break;
            case "group":
                Log::InsertMsgLog($data['message'], $data['user_id'], $data['group_id'], "", 0, "", $message_type);
                $user_id = $data['user_id'];
                $message = $data['message'];
                $group_id = $data['group_id'];
//                exit('{"reply":"' . addslashes(json_encode($data, JSON_UNESCAPED_UNICODE)) . '","block":true}');
                /*
                 * 判断用户是否为黑名单用户 或 未开启群消息回复
                 */
                if ($Robot->is_qq_black_list || !$Robot->is_on_group) {
                    if (MsgTool::in_Array($user_id, $Robot->qq_black_list)) {
                        exit('{"block": true}');
                    }
                }
                /*
                 * 判断是否开启 群白名单黑名单
                 */
                if (!$Robot->is_group_black_list && !$Robot->is_group_white_list) {
                    /*
                     * 白黑名单均未开启  继续判断  是否开启 @
                     */
                    if ($Robot->is_at && MsgTool::in_String(CQ::enAtCode(QQ), $message)) {
                        /*
                         *  开启 @ 继续判断是否开启关键字匹配
                         */
                        if ($Robot->is_keyword && MsgTool::Array_In_String($message, $Robot->keyword)) {

                            /*
                             * 开启关键字 将消息下发到插件
                             */
                            $Plugin = runOders($PluginOrders, $data, $Robot);
                        } elseif (!$Robot->is_keyword) {
                            /*
                             * 开启 @  无其余任何限制
                             */
                            $Plugin = runOders($PluginOrders, $data, $Robot);

                        }
                    } elseif (!$Robot->is_at) {
                        if ($Robot->is_keyword && MsgTool::Array_In_String($message, $Robot->keyword)) {
                            $Plugin = runOders($PluginOrders, $data, $Robot);
                        } elseif (!$Robot->is_keyword) {
                            $Plugin = runOders($PluginOrders, $data, $Robot);
                        }
                    }
                } else if ($Robot->is_group_white_list) {
                    if (MsgTool::in_Array($group_id, $Robot->group_white_list)) {
                        /*
                         * 白黑名单开启  继续判断  是否开启 @
                         */
                        if ($Robot->is_at && MsgTool::in_String(CQ::enAtCode(QQ), $message)) {
                            /*
                             *  开启 @ 继续判断是否开启关键字匹配
                             */
                            if ($Robot->is_keyword && MsgTool::Array_In_String($message, $Robot->keyword)) {
                                /*
                                 * 将消息下发到插件
                                 */
                                $Plugin = runOders($PluginOrders, $data, $Robot);
                            } elseif (!$Robot->is_keyword) {
                                /*
                                 * 无任何限制
                                 */
                                $Plugin = runOders($PluginOrders, $data, $Robot);
                            }
                        } elseif (!$Robot->is_at) {
                            if ($Robot->is_keyword && MsgTool::Array_In_String($message, $Robot->keyword)) {
                                $Plugin = runOders($PluginOrders, $data, $Robot);
                            } elseif (!$Robot->is_keyword) {
                                $Plugin = runOders($PluginOrders, $data, $Robot);
                            }
                        }
                    }
                } else if ($Robot->is_group_black_list) {
                    if (!MsgTool::in_Array($group_id, $Robot->group_black_list)) {
                        /*
                         * 黑名开启  继续判断  是否开启 @
                         */
                        if ($Robot->is_at && MsgTool::in_String(CQ::enAtCode(QQ), $message)) {
                            /*
                             *  开启 @ 继续判断是否开启关键字匹配
                             */
                            if ($Robot->is_keyword && MsgTool::Array_In_String($message, $Robot->keyword)) {
                                /*
                                 * 将消息下发到插件
                                 */
                                runOders($PluginOrders, $data, $Robot);
                            } elseif (!$Robot->is_keyword) {
                                /*
                                 * 无任何限制
                                 */
                                runOders($PluginOrders, $data, $Robot);
                            }
                        } elseif (!$Robot->is_at) {
                            if ($Robot->is_keyword && MsgTool::Array_In_String($message, $Robot->keyword)) {
                                runOders($PluginOrders, $data, $Robot);
                            } elseif (!$Robot->is_keyword) {
                                runOders($PluginOrders, $data, $Robot);
                            }
                        }
                    }
                }
                break;
            case "discuss":
                /*
                * 判断是否开启讨论组消息回复
                */
                if (!$Robot->is_on_discuss) {
                    exit('{"block": true}');
                }
                Log::InsertMsgLog($data['message'], $data['user_id'], "", $data['discuss_id'], 0, "", $message_type);
                $Plugin = runOders($PluginOrders, $data, $Robot);
                break;
        }



        if ((($Plugin != null && !$Plugin->isIntercept()) || $Plugin == null) && (!$Robot->is_at || (($message_type == 'group' && $Robot->is_at && MsgTool::in_String(CQ::enAtCode(QQ), $message)) || $message_type != "group"))) {
            $message_type = isset($data['message_type']) ? $data['message_type'] : "";
            $sub_type = isset($data['sub_type']) ? $data['sub_type'] : "";
            $group_id = isset($data['group_id']) ? $data['group_id'] : "";
            $discuss_id = isset($data['discuss_id']) ? $data['discuss_id'] : "";
            Log::InsertMsgLog($data['message'], $data['user_id'], $group_id, $discuss_id, 1, "消息已推送至插件:图灵插件", $message_type, $sub_type);
            $Plugin = Robot::runPlugin("TulingPlugin", $data, $Robot);
        }


        break;

    case 'event':

        $event = $data['event'];
        /*
         *  事件Log日志
         */
        switch ($event) {
            case "group_admin":
                $sub_type = $data['sub_type'];
                Log::InsertEventLog($data['user_id'], $data['group_id'], "", 0, "", $event, $sub_type);
                break;
            case "group_decrease":
                $sub_type = $data['sub_type'];
                Log::InsertEventLog($data['user_id'], $data['group_id'], $data['operator_id'], 0, "", $event, $sub_type);
                break;
            case "group_increase":
                $sub_type = $data['sub_type'];
                Log::InsertEventLog($data['user_id'], $data['group_id'], $data['operator_id'], 0, "", $event, $sub_type);
                break;
            case "friend_added":
                Log::InsertEventLog($data['user_id'], "", "", 0, "", $event, "");
                break;
        }
        /*
         * 将事件下发到插件
         *  插件中命令包含 * 则代表监控事件
         */
        foreach ($PluginOrders AS $order) {
            if ($order['order_name'] == '*' && $order['status']) {
                /*
                 * 插件 Log 日志
                 */
                switch ($event) {
                    case "group_admin":
                        $sub_type = $data['sub_type'];
                        Log::InsertEventLog($data['user_id'], $data['group_id'], "", 1, "事件已推送至插件:" . $order['name'], $event, $sub_type);
                        break;
                    case "group_decrease":
                        $sub_type = $data['sub_type'];
                        Log::InsertEventLog($data['user_id'], $data['group_id'], $data['operator_id'], 1, "事件已推送至插件:" . $order['name'], $event, $sub_type);
                        break;
                    case "group_increase":
                        $sub_type = $data['sub_type'];
                        Log::InsertEventLog($data['user_id'], $data['group_id'], $data['operator_id'], 1, "事件已推送至插件:" . $order['name'], $event, $sub_type);
                        break;
                    case "friend_added":
                        Log::InsertEventLog($data['user_id'], "", "", 1, "事件已推送至插件:" . $order['name'], $event, "");
                        break;
                }
                $Plugin = Robot::runPlugin($order['plugin_class'], $data, $Robot);
                if ($Plugin != null && $Plugin->isIntercept) {
                    echo '{"block": true}';
                    break;
                }
            }
        }
        break;

    case 'request':
        $request_type = $data['request_type'];
        /*
         * 请求 Log 日志
         */
        switch ($request_type) {
            case "friend":
                Log::InsertRequestLog($data['message'], $data['flag'], $data['user_id'], "", 0, "", $request_type, "");
                break;
            case "group":
                $sub_type = $data['sub_type'];
                Log::InsertRequestLog($data['message'], $data['flag'], $data['user_id'], $data['group_id'], 0, "", $request_type, $sub_type);
                break;
        }
        /*
         *  插件中命令包含 * 则代表监控事件
         */
        foreach ($PluginOrders AS $order) {
            if ($order['order_name'] == '*' && $order['status']) {
                /*
                 * 插件 Log 日志
                 */
                switch ($request_type) {
                    case "friend":
                        Log::InsertRequestLog($data['message'], $data['flag'], $data['user_id'], "", 1, "请求已推送至插件:" . $order['name'], $request_type, "");
                        break;
                    case "group":
                        $sub_type = $data['sub_type'];
                        Log::InsertRequestLog($data['message'], $data['flag'], $data['user_id'], $data['group_id'], 1, "请求已推送至插件:" . $order['name'], $request_type, $sub_type);
                        break;
                }
                $Plugin = Robot::runPlugin($order['plugin_class'], $data, $Robot);
                if ($Plugin != null && $Plugin->isIntercept) {
                    echo '{"block": true}';
                    break;
                }
            }
        }

        break;

    default:
        # code...

        break;

}

function runOders($PluginOrders, $data, $Robot)
{
    $Plugin = null;
    foreach ($PluginOrders AS $order) {
        $pro = explode($order['order_name'], $data['message']);
        if ((count($pro) >= 2 && $order['status']) || $order['order_name'] == '*') {
            /*
            *  插件Log日志
            */
            $message_type = $data['message_type'];
            $Plugin = Robot::runPlugin($order['plugin_class'], $data, $Robot);
            if ($Plugin != null) {
                switch ($message_type) {
                    case "private":
                        $sub_type = $data['sub_type'];
                        Log::InsertMsgLog($data['message'], $data['user_id'], "", "", 1, "消息已推送至插件:" . $order['name'], $message_type, $sub_type);
                        break;
                    case "group":
                        Log::InsertMsgLog($data['message'], $data['user_id'], $data['group_id'], "", 1, "消息已推送至插件:" . $order['name'], $message_type);
                        break;
                    case "discuss":
                        Log::InsertMsgLog($data['message'], $data['user_id'], "", $data['discuss_id'], 1, "消息已推送至插件:" . $order['name'], $message_type);
                        break;
                }
            }
            if ($Plugin != null && $Plugin->isIntercept() == true) {
                echo '{"block": true}';
                break;
            }
        }
    }
    return $Plugin;

}


?>