<?php

/**
 * Created by PhpStorm.
 * User: Kilingzhang
 * Date: 2017/6/30
 * Time: 19:10
 */
class Hook
{
    //action hooks array
    private static $actions = array();
    /**
     * ads a function to an action hook
     * @param $hook
     * @param $function
     */
    public static function add_action($hook,$function)
    {
        $hook=mb_strtolower($hook,'UTF-8');
        // create an array of function handlers if it doesn't already exist
        if(!self::exists_action($hook))
        {
            self::$actions[$hook] = array();
        }
        // append the current function to the list of function handlers
        if (is_callable($function))
        {
            self::$actions[$hook][] = $function;
            return true;
        }
        return false ;
    }
    /**
     * executes the functions for the given hook
     * @param string $hook
     * @param array $params
     * @return boolean true if a hook was setted
     */
    public static function do_action($hook,$params=NULL)
    {
        $hook=mb_strtolower($hook,'UTF-8');
        if(isset(self::$actions[$hook]))
        {
            // call each function handler associated with this hook
            foreach(self::$actions[$hook] as $function)
            {
                if (is_array($params))
                {
                    call_user_func_array($function,$params);
                }
                else
                {
                    call_user_func($function);
                }
                //cant return anything since we are in a loop! dude!
            }
            return true;
        }
        return false;
    }
    /**
     * gets the functions for the given hook
     * @param string $hook
     * @return mixed
     */
    public static function get_action($hook)
    {
        $hook=mb_strtolower($hook,'UTF-8');
        return (isset(self::$actions[$hook]))? self::$actions[$hook]:false;
    }
    /**
     * check exists the functions for the given hook
     * @param string $hook
     * @return boolean
     */
    public static function exists_action($hook)
    {
        $hook=mb_strtolower($hook,'UTF-8');
        return (isset(self::$actions[$hook]))? true:false;
    }
}


/**
 * Hook docs
 */
/**
 *
 *

function helloworld(){
    echo "helloworld";
}

//添加钩子
Hook::add_action('unique_name_hook','helloworld');
//或使用快捷函数添加钩子:

//执行钩子
Hook::do_action('unique_name_hook');//也可以使用 Hook::do_action();
 *
 *
 */