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
define("dbTable","CoolQ_v3");
define('dbPort', 3306);




/**
 *  PASSWORD TOKEN
 */

//Encode
define('ENCODE_CIPHER', MCRYPT_RIJNDAEL_128);
define('ENCODE_MODE', MCRYPT_MODE_ECB);
define('ENCODE_KEY', '');