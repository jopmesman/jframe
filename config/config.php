<?php

if (file_exists('config/own_config.php')) {
  /**
   * Is this file you can override the config file.
   * Add items of the config here
   * Just add the define.
   *
   */
  require_once 'own_config.php';
}

//The absolute path to the index.php
if (!defined('SERVER_ROOT')) define('SERVER_ROOT', '/web/httpdocs/');
//The site root
if (!defined('SITE_ROOT')) define('SITE_ROOT', 'http://www.example.com/');
if (!defined('BASE_PATH')) define('BASE_PATH', '/');
//Waths the name of the main template (without the .php suffix
if (!defined('MAIN_TEMPLATE')) define('MAIN_TEMPLATE', 'main');

//Database Host
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
//Database username
if (!defined('DB_USERNAME')) define("DB_USERNAME", 'username');
//Database password
if (!defined('DB_PASSWORD')) define('DB_PASSWORD', 'password');
//Database name
if (!defined('DB_DATABASE')) define('DB_DATABASE', 'jframe');
//What is the driver of the database?
if (!defined('DB_DRIVER')) define('DB_DRIVER', 'mysql');

//this creates an array key in the session. There will be no clashes with other session data
if (!defined('SESSNAME')) define('SESSNAME', 'xx');