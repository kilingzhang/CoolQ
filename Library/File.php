<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace Library;



/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/1/19
 * Time: 3:37
 */


#文件目录操作类

#例子：
$file = new file();
//$fileutil->createDir('a/1/2/3');          #测试建立文件夹  建一个a/1/2/3文件夹
//$fileutil->copyDir('a', 'd/e');			 #测试复制文件夹  建立一个d/e文件夹，把a文件夹下的内容复制进去
//$fileutil->moveDir('a/', 'b/c');		 #测试移动文件夹  建立一个b/c文件夹,并把a文件夹下的内容移动进去，并删除a文件夹
//$fileutil->unlinkDir('F:/d');                    #测试删除文件夹  删除d文件夹

//$fileutil->createFile('b/1/2/3.txt');            #测试建立文件    在b/1/2/文件夹下面建一个3.txt文件
//$fileutil->writeFile('b/1/2/3.txt','i write!');  #在文件中写内容
//$fileutil->copyFile('b/1/2/3.txt','b/b/3.txt');  #测试复制文件    建立一个b/b文件夹，并把b/1/2文件夹中的3.txt文件复制进去
//$fileutil->moveFile('b/1/2/3.txt','b/d/3.txt');  #测试移动文件    建立一个b/d文件夹，并把b/1/2中的3.exe移动进去
//$fileutil->unlinkFile('b/d/3.exe');              #测试删除文件    删除b/d/3.exe文件
//
//$list = $fileutil->dirList("../");        #测试列表文件夹  列出目录下所有文件
//$list = $fileutil->dirTree("../");          #测试列表文件夹树  列出目录下所有文件直接直接的树关系
//print_r($list);

//$arr = $fileutil->readFile2array('example/mysql.txt');
//$arr = $fileutil->readsFile('example/mysql.txt');
//$size=$fileutil->bitSize($fileutil->getDirSize("example"));      #得到文件或目录的大小

class File {
    /**
    建立文件夹
     *
    @param  string $aimUrl
    @return  viod
     */
    public static function createDir($aimUrl, $mode = 0777) {
        $aimUrl = str_replace('', '/', $aimUrl);
        $aimDir = '';
        $arr = explode('/', $aimUrl);
        foreach ($arr as $str) {
            $aimDir .= $str . '/';
            if (!file_exists($aimDir)) {
                mkdir($aimDir, $mode);
            }
        }
    }

    /**
    建立文件
     *
    @param  string  $aimUrl
    @param  boolean  $overWrite 该参数控制是否覆盖原文件
    @return  boolean
     */
    public static function createFile($aimUrl, $overWrite = false) {
        if (file_exists($aimUrl) && $overWrite == false) {
            return false;
        } elseif (file_exists($aimUrl) && $overWrite == true) {
            self::unlinkFile($aimUrl);
        }
        $aimDir = dirname($aimUrl);
        self::createDir($aimDir);
        touch($aimUrl);
        return true;
    }

    /**
    移动文件夹
     *
    @param  string  $oldDir
    @param  string  $aimDir
    @param  boolean  $overWrite 该参数控制是否覆盖原文件
    @return  boolean
     */
    public static function moveDir($oldDir, $aimDir, $overWrite = false) {
        $aimDir = str_replace('', '/', $aimDir);
        $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
        $oldDir = str_replace('', '/', $oldDir);
        $oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';
        if (!is_dir($oldDir)) {
            return false;
        }
        if (!file_exists($aimDir)) {
            self::createDir($aimDir);
        }
        @$dirHandle = opendir($oldDir);
        if (!$dirHandle) {
            return false;
        }
        while (false !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!is_dir($oldDir . $file)) {
                self::moveFile($oldDir . $file, $aimDir . $file, $overWrite);
            } else {
                self::moveDir($oldDir . $file, $aimDir . $file, $overWrite);
            }
        }
        closedir($dirHandle);
        return $overWrite ? rmdir($oldDir) : true;
    }

    /**
    移动文件
     *
    @param  string  $fileUrl
    @param  string  $aimUrl
    @param  boolean  $overWrite 该参数控制是否覆盖原文件
    @return  boolean
     */
    public static function moveFile($fileUrl, $aimUrl, $overWrite = false) {
        if (!file_exists($fileUrl)) {
            return false;
        }
        if (file_exists($aimUrl) && $overWrite = false) {
            return false;
        } elseif (file_exists($aimUrl) && $overWrite = true) {
            self::unlinkFile($aimUrl);
        }
        $aimDir = dirname($aimUrl);
        self::createDir($aimDir);
        rename($fileUrl, $aimUrl);
        return true;
    }

    /**
    删除文件夹
     *
    @param  string  $aimDir
    @return  boolean
     */
    public static function unlinkDir($aimDir) {
        $aimDir = str_replace('', '/', $aimDir);
        $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
        if (!is_dir($aimDir)) {
            return false;
        }
        $dirHandle = opendir($aimDir);
        while (false !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!is_dir($aimDir . $file)) {
                self::unlinkFile($aimDir . $file);
            } else {
                self::unlinkDir($aimDir . $file);
            }
        }
        closedir($dirHandle);
        return rmdir($aimDir);
    }

    /**
    删除文件
     *
    @param  string  $aimUrl
    @return  boolean
     */
    public static function unlinkFile($aimUrl) {
        if (file_exists($aimUrl)) {
            unlink($aimUrl);
            return true;
        } else {
            return false;
        }
    }

    /**
    复制文件夹
     *
    @param  string  $oldDir
    @param  string  $aimDir
    @param  boolean  $overWrite 该参数控制是否覆盖原文件
    @return  boolean
     */
    public static function copyDir($oldDir, $aimDir, $overWrite = false) {
        $aimDir = str_replace('', '/', $aimDir);
        $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
        $oldDir = str_replace('', '/', $oldDir);
        $oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';
        if (!is_dir($oldDir)) {
            return false;
        }
        if (!file_exists($aimDir)) {
            self::createDir($aimDir);
        }
        $dirHandle = opendir($oldDir);
        while (false !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!is_dir($oldDir . $file)) {
                self::copyFile($oldDir . $file, $aimDir . $file, $overWrite);
            } else {
                self::copyDir($oldDir . $file, $aimDir . $file, $overWrite);
            }
        }
        return closedir($dirHandle);
    }

    /**
    复制文件
     *
    @param  string  $fileUrl
    @param  string  $aimUrl
    @param  boolean  $overWrite 该参数控制是否覆盖原文件
    @return  boolean
     */
    public static function copyFile($fileUrl, $aimUrl, $overWrite = false) {
        if (!file_exists($fileUrl)) {
            return false;
        }
        if (file_exists($aimUrl) && $overWrite == false) {
            return false;
        } elseif (file_exists($aimUrl) && $overWrite == true) {
            self::unlinkFile($aimUrl);
        }
        $aimDir = dirname($aimUrl);
        self::createDir($aimDir);
        copy($fileUrl, $aimUrl);
        return true;
    }

    /**
    将字符串写入文件
     *
    @param  string  $filename 文件名
    @param  boolean $str 待写入的字符数据
     */
    public static function writeFile($filename, $str) {
        if (function_exists(file_put_contents)) {
            file_put_contents($filename, $str);
        } else {
            $fp = fopen($filename, "wb");
            fwrite($fp, $str);
            fclose($fp);
        }
    }

    /**
    将整个文件内容读出到一个字符串中
     *
    @param  string  $filename 文件名
    @return array
     */
    public static function readsFile($filename) {
        if (function_exists(file_get_contents)) {
            return file_get_contents($filename);
        } else {
            $fp = fopen($filename, "rb");
            $str = fread($fp, filesize($filename));
            fclose($fp);
            return $str;
        }
    }

    /**
    将文件内容读出到一个数组中
     *
    @param  string  $filename 文件名
    @return array
     */
    public static function readFile2array($filename) {
        $file = file($filename);
        $arr = array();
        foreach ($file as $value) {
            $arr [] = trim($value);
        }
        return $arr;
    }

    /**
    转化 \ 为 /
     *
    @param	string	$path	路径
    @return	string	路径
     */
    public static function dirPath($path) {
        $path = str_replace('\\', '/', $path);
        if (substr($path, -1) != '/')
            $path = $path . '/';
        return $path;
    }

    /**
    转换目录下面的所有文件编码格式
     *
    @param	string	$in_charset		原字符集
    @param	string	$out_charset	目标字符集
    @param	string	$dir			目录地址
    @param	string	$fileexts		转换的文件格式
    @return	string	如果原字符集和目标字符集相同则返回false，否则为true
     */
    public static function dirIconv($in_charset, $out_charset, $dir, $fileexts = 'php|html|htm|shtml|shtm|js|txt|xml') {
        if ($in_charset == $out_charset)
            return false;
        $list = self::dirList($dir);
        foreach ($list as $v) {
            if (preg_match("/\.($fileexts)/i", $v) && is_file($v)) {
                file_put_contents($v, iconv($in_charset, $out_charset, file_get_contents($v)));
            }
        }
        return true;
    }

    /**
    列出目录下所有文件
     *
    @param	string	$path		路径
    @param	string	$exts		扩展名
    @param	array	$list		增加的文件列表
    @return	array	所有满足条件的文件
     */
    public static function dirList($path, $exts = '', $list = array()) {
        $path = self::dirPath($path);
        $files = glob($path . '*');
        foreach ($files as $v) {
            $fileext = self::fileext($v);
            if (!$exts || preg_match("/\.($exts)/i", $v)) {
                $list [] = $v;
                if (is_dir($v)) {
                    $list = self::dirList($v, $exts, $list);
                }
            }
        }
        return $list;
    }

    /**
    设置目录下面的所有文件的访问和修改时间
     *
    @param	string	$path		路径
    @param	int		$mtime		修改时间
    @param	int		$atime		访问时间
    @return	array	不是目录时返回false，否则返回 true
     */
    public static function dirTouch($path, $mtime = TIME, $atime = TIME) {
        if (!is_dir($path))
            return false;
        $path = self::dirPath($path);
        if (!is_dir($path))
            touch($path, $mtime, $atime);
        $files = glob($path . '*');
        foreach ($files as $v) {
            is_dir($v) ? self::dirTouch($v, $mtime, $atime) : touch($v, $mtime, $atime);
        }
        return true;
    }

    /**
    目录列表
     *
    @param	string	$dir		路径
    @param	int		$parentid	父id
    @param	array	$dirs		传入的目录
    @return	array	返回目录及子目录列表
     */
    public static function dirTree($dir, $parentid = 0, $dirs = array()) {
        global $id;
        if ($parentid == 0)
            $id = 0;
        $list = glob($dir . '*');
        foreach ($list as $v) {
            if (is_dir($v)) {
                $id++;
                $dirs [$id] = array('id' => $id, 'parentid' => $parentid, 'name' => basename($v), 'dir' => $v . '/');
                $dirs = self::dirTree($v . '/', $id, $dirs);
            }
        }
        return $dirs;
    }

    /**
    目录列表
     *
    @param	string	$dir		路径
    @return	array	返回目录列表
     */
    public static function dirNodeTree($dir) {
        $d = dir($dir);
        $dirs = array();
        while (false !== ($entry = $d->read())) {
            if ($entry != '.' and $entry != '..' and is_dir($dir . '/' . $entry)) {
                $dirs[] = $entry;
            }
        }
        return $dirs;
    }

    /**
    获取目录大小
     *
    @param	string	$dirname	目录
    @return	string	  比特B
     */
    public static function getDirSize($dirname) {
        if (!file_exists($dirname) or !is_dir($dirname))
            return false;
        if (!$handle = opendir($dirname))
            return false;
        $size = 0;
        while (false !== ($file = readdir($handle))) {
            if ($file == "." or $file == "..")
                continue;
            $file = $dirname . "/" . $file;
            if (is_dir($file)) {
                $size += self::getDirSize($file);
            } else {
                $size += filesize($file);
            }

        }
        closedir($handle);
        return $size;
    }

    /**
     * 将字节转换成Kb或者Mb...
     * 参数 $size为字节大小
     */
    public static function bitSize($size) {
        if (!preg_match("/^[0-9]+$/", $size))
            return 0;
        $type = array("B", "KB", "MB", "GB", "TB", "PB");

        $j = 0;
        while ($size >= 1024) {
            if ($j >= 5)
                return $size . $type [$j];
            $size = $size / 1024;
            $j++;
        }
        return $size . $type [$j];
    }

    /**
    获取文件名后缀
     *
    @param	string	$filename
    @return	string
     */
    public static function fileext($filename) {
        return addslashes(trim(substr(strrchr($filename, '.'), 1, 10)));
    }

    public static function remote_file_exists($url_file) {
        $url_file = trim($url_file);
        if (empty($url_file)) return false;
        $url_arr = parse_url($url_file);
        if (!is_array($url_arr) || empty($url_arr)) return false;
        $host = $url_arr['host'];
        $path = $url_arr['path'] . "?" . $url_arr['query'];
        $port = isset($url_arr['port']) ? $url_arr['port'] : "80";
        $fp = fsockopen($host, $port, $err_no, $err_str, 30);
        if (!$fp) return false;
        $request_str = "GET " . $path . " HTTP/1.1\r\n";
        $request_str .= "Host:" . $host . "\r\n";
        $request_str .= "Connection:Close\r\n\r\n";
        fwrite($fp, $request_str);
        //fread replace fgets
        $first_header = fread($fp, 128);
        fclose($fp);
        if (trim($first_header) == "") return false;
        //check $url_file "Content-Location"
        if (!preg_match("/200/", $first_header) || preg_match("/Location:/", $first_header)) return false;
        return true;
    }

    /**
    修改文件名
     *
    @param  string  $fileUrl
    @param  boolean  $overWrite 该参数控制是否覆盖原文件
    @return  boolean
     */
    public  static function fileDirToChart($fileUrl,$overWrite = false){


        if(!file_exists($fileUrl) || !is_dir($fileUrl)){
            if(is_file($fileUrl)){
                if($fileUrl != Pinyin::getPinyin(iconv(mb_detect_encoding($fileUrl,array(
                        "ASCII",'UTF-8','GB2312',"GBK",'BIG5'
                    )),"utf-8",$fileUrl))){
                    self::moveFile($fileUrl,Pinyin::getPinyin(iconv(mb_detect_encoding($fileUrl,array(
                        "ASCII",'UTF-8','GB2312',"GBK",'BIG5'
                    )),"utf-8",$fileUrl)));

                    return true;
                }
                return false;
            }
            return false;
        }

        if(count(self::dirTree($fileUrl)) == 0){
            foreach (self::dirList($fileUrl) as $v) {
                self::moveFile($v,Pinyin::getPinyin(@iconv(mb_detect_encoding($v,array(
                    "ASCII",'UTF8','GB2312',"GBK",'BIG5'
                )),"utf-8",$v)),true);
            }
            return true;
        }
        foreach(self::dirTree($fileUrl) as $value){

            if($value['parentid'] == 0){
                $names = self::dirList($value['dir']);
                foreach ($names as $key => $v){
                    $links[$key] = Pinyin::getPinyin(@iconv(mb_detect_encoding($v,array(
                        "ASCII",'UTF8','GB2312',"GBK",'BIG5'
                    )),"utf-8",$v));
                }
            }

            if($value['dir'] != Pinyin::getPinyin(@iconv(mb_detect_encoding($value['dir'],array(
                    "ASCII",'UTF8','GB2312',"GBK",'BIG5'
                )),"utf-8",$value['dir']))){
                self::moveDir($value['dir'],Pinyin::getPinyin(@iconv(mb_detect_encoding($value['dir'],array(
                    "ASCII",'UTF8','GB2312',"GBK",'BIG5'
                )),"utf-8",$value['dir'])));
            }

        }

        foreach ($names as $key => $value){
            if($value != Pinyin::getPinyin(iconv(mb_detect_encoding($value,array(
                    "ASCII",'UTF-8','GB2312',"GBK",'BIG5'
                )),"utf-8",$value))){
                self::moveFile($value,Pinyin::getPinyin(iconv(mb_detect_encoding($value,array(
                    "ASCII",'UTF-8','GB2312',"GBK",'BIG5'
                )),"utf-8",$value)));
//                show($value);
//                show($value,Pinyin::getPinyin(@iconv(mb_detect_encoding($value,array(
//                    "ASCII",'UTF-8','GB2312',"GBK",'BIG5'
//                )),"utf-8",$value)));

            }
        }

    }
}

//
//class FileType
//{
//    private static $_TypeList=array();
//    private static $CheckClass=null;
//    private public static function __construct($filename)
//    {
//        self::$_TypeList=self::getTypeList();
//    }
//
//    /**
//     *处理文件类型映射关系表*
//     *
//     * @param string $filename 文件类型
//     * @return string 文件类型，没有找到返回：other
//     */
//    private public static function _getFileType($filename)
//    {
//        $filetype="other";
//        if(!file_exists($filename)) throw new Exception("no found file!");
//        $file = @fopen($filename,"rb");
//        if(!$file) throw new Exception("file refuse!");
//        $bin = fread($file, 15); //只读15字节 各个不同文件类型，头信息不一样。
//        fclose($file);
//
//        $typelist=self::$_TypeList;
//        foreach ($typelist as $v)
//        {
//            $blen=strlen(pack("H*",$v[0])); //得到文件头标记字节数
//            $tbin=substr($bin,0,intval($blen)); ///需要比较文件头长度
//
//            if(strtolower($v[0])==strtolower(@array_shift(unpack("H*",$tbin))))
//            {
//                return $v[1];
//            }
//        }
//        return $filetype;
//    }
//
//    /**
//     *得到文件头与文件类型映射表*
//     *
//     * @return array array(array('key',value)...)
//     */
//    public public static function getTypeList()
//    {
//        return array(array("FFD8FFE1","jpg"),
//            array("89504E47","png"),
//            array("47494638","gif"),
//            array("49492A00","tif"),
//            array("424D","bmp"),
//            array("41433130","dwg"),
//            array("38425053","psd"),
//            array("7B5C727466","rtf"),
//            array("3C3F786D6C","xml"),
//            array("68746D6C3E","html"),
//            array("44656C69766572792D646174","eml"),
//            array("CFAD12FEC5FD746F","dbx"),
//            array("2142444E","pst"),
//            array("D0CF11E0","xls/doc"),
//            array("5374616E64617264204A","mdb"),
//            array("FF575043","wpd"),
//            array("252150532D41646F6265","eps/ps"),
//            array("255044462D312E","pdf"),
//            array("E3828596","pwl"),
//            array("504B0304","zip"),
//            array("52617221","rar"),
//            array("57415645","wav"),
//            array("41564920","avi"),
//            array("2E7261FD","ram"),
//            array("2E524D46","rm"),
//            array("000001BA","mpg"),
//            array("000001B3","mpg"),
//            array("6D6F6F76","mov"),
//            array("3026B2758E66CF11","asf"),
//            array("4D546864","mid"));
//    }
//
//    public static public static function   getFileType($filename)
//    {
//        if(!self::$CheckClass) self::$CheckClass=new self($filename);
//        $class=self::$CheckClass;
//        return $class->_getFileType($filename);
//    }
//}
