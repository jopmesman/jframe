<?php

//The absolute path to the index.php
define('SERVER_ROOT', '/volume1/web/jframe/');
//The site root
define('SITE_ROOT', 'http://192.168.1.60/jframe//');
define('BASE_PATH', '/jframe/');
//Waths the name of the main template (without the .php suffix
define('MAIN_TEMPLATE', 'main');

//Database Host
define('DB_HOST', 'localhost');
//Database username
define("DB_USERNAME", 'root');
//Database password
define('DB_PASSWORD', 'root');
//Database name
define('DB_DATABASE', 'jframe');
//What is the driver of the database?
define('DB_DRIVER', 'mysql');

//this creates an array key in the session. There will be no clashes with other session data
define('SESSNAME', 'xx');