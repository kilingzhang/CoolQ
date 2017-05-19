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
define("Hash","");


/**
 *  Tuling Api Verification
 */
define("APIkey","");
define("secret","");


//CoolQ HTTP API
define("PATH","http://127.0.0.1");
define("PORT",5700);
define("TOKEN","");
define("QQ",);
define("MANAGERQQ",);


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
define("dbPassword","");
define("dbTable","CoolQ");
define('dbport', 3306);




/**
 *  PASSWORD TOKEN
 */

//Encode
define('ENCODE_CIPHER', MCRYPT_RIJNDAEL_128);
define('ENCODE_MODE', MCRYPT_MODE_ECB);
define('ENCODE_KEY', '');