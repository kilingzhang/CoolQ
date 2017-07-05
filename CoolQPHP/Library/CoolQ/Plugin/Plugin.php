<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/2/14
 * Time: 13:37
 */
namespace CoolQ\Plugin;

class Plugin
{


    public $getData;
    public $Intercept;
    public $Robot;
    public static $Plugin = null;

    public function __construct($getData, $Robot)
    {

        $this->Robot = $Robot;
        $this->getData = $getData;
        $this->Intercept = false;

    }

    /**
     * @param bool $bool
     */
    public function setIntercept($bool = true)
    {
        $this->Intercept = $bool;
    }

    /**
     * @return bool
     */
    public function isIntercept()
    {
        return $this->Intercept;
    }


    public function isManager($QQ)
    {

    }

    /**
     * @return mixed
     */
    public function getGetData()
    {
        return $this->getData;
    }

    /**
     * @return mixed
     */
    public function getRobot()
    {
        return $this->Robot;
    }


}