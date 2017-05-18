<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/5 0005
 * Time: 13:56
 */
class DB{

    private $dbHost;
    private $dbUser;
    private $dbPassword;
    private $dbTable;
    private $dbport;
    public $link;
    private $result;

    /**
     * 初始化数据链接数据库
     *
     * @param string $dbHost
     * * @param string $dbUser
     * * @param string $dbPassword
     * * @param string $dbTable
     * * @param string $dbport
     */
    public function __construct($dbHost, $dbUser, $dbPassword, $dbTable, $dbport){
        $this->dbHost = $dbHost;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->dbTable = $dbTable;
        $this->dbport = $dbport;
        $this->connect($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbTable, $this->dbport);
    }

    /**
     * 接数据库
     *
     * @param string $dbHost
     * * @param string $dbUser
     * * @param string $dbPassword
     * * @param string $dbTable
     * * @param string $dbport
     */
    public function connect($dbHost, $dbUser, $dbPassword, $dbTable, $dbport)
    {
        $this->link = @mysqli_connect($dbHost, $dbUser, $dbPassword, $dbTable, $dbport);
        if (mysqli_connect_errno()) {
            exit(mysqli_connect_error());
        }
        mysqli_set_charset($this->link, 'utf8');
        return $this->link;
    }

    /**
     * 插入一组数据或一条数据
     *
     * @param string $sql
     * @param   ""   $link
     */
    public function query($sql, $link = "")
    {
        if ($link == "") {

            $link = $this->link;

        }
        $result = mysqli_query($link, $sql);
        if (mysqli_errno($link)) {
            exit(mysqli_error($link));
        }

        return $result;
    }

    /**
     * 得到数据集中的一条信息
     *
     * @param 数据集 $result
     */
    public function getone($result)
    {

        $result = mysqli_fetch_assoc($result);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 得到数据集中的所有信息
     *
     * @param 数据集 $result
     */
    public function getall($result)
    {
        $result = mysqli_fetch_array($result);
        return $result;
    }


    /**
     *
     */
    public function is_Empty($data,$table,$key){
        $sql="select *  from $table where $key='$data' ";

        $result=$this->query($sql);

        $result=$this->getone($result);

        if($result[$key]==$data){
            return true;
        }else{
            return false;
        }

    }

    public  function upData($table,$valKey,$val,$dataKey,$data){
        $sql="UPDATE '$table' SET '$dataKey'='$data' WHERE '$valKey'='$val' ";
        $result = $this->query($sql);
        if($result){
            return true;
        }else{
            return false;
        }

    }

    public function Log($sid,$log_record){
        $time = date("y-m-d H-i-s");
        $ip = $_SERVER['SERVER_ADDR'];
        $sql = "INSERT INTO  log  (`sid`,`log_time`,`log_record`,`ip`) VALUES ( $sid, $time,$log_record, $ip)";
        $result = $this->query($sql);
        if($result){
            $json['code'] = 0;
            $json['msg'] ="Log success!" ;
            $json['data'] = $log_record;
            $json = json_encode($json,JSON_UNESCAPED_UNICODE);
            return $json;
        }else{
            $json['code'] = 2333;
            $json['msg'] ="Log Failed!" ;
            $json['data'] = $log_record;
            $json = json_encode($json,JSON_UNESCAPED_UNICODE);
            return $json;
        }

    }


}


//$DB = new DB(dbHost, dbUser, dbPassword, dbTable, dbport);
