<?php

/**
 *  Charset  UTF-8
 */
header('Content-type:text/html;charset=utf-8');

/**
 *  Access-Control-Allow-Origin
 */
//header("Access-Control-Allow-Origin: *");



/**
 *  API Verification
 *
 *  Role
 *  Hash
 */
define("Role","YiBao");
define("Hash","30e9719f4fd15c3f01eb87a6770ff60d");


/**
 *  Tuling Api Verification
 */
define("APIkey","d400e0967d44447eb11afd8ea5ea2b11");
define("secret","8a08a86c4ae39f34");


//CoolQPHP HTTP API
define("PATH","http://127.0.0.1");
define("PORT",5700);
define("TOKEN","");
define("QQ",1246002938);
define("MANAGERQQ",1353693508);


/**
 * Database
 * dbHost
 * dbUser
 * dbPassword
 * dbTable
 * dbport
 *
 */
define('dbHost', '127.0.0.1');
define("dbUser","root");
define("dbPassword","wxhxa.666Z");
define("dbTable","CoolQ_V2");
define('dbPort', 3306);




/**
 *  PASSWORD TOKEN
 */

//Encode
define('ENCODE_CIPHER', MCRYPT_RIJNDAEL_128);
define('ENCODE_MODE', MCRYPT_MODE_ECB);
define('ENCODE_KEY', '93c5680f1d6f3c34036092204ef58b9d');