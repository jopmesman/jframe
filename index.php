<?php
session_start();

//Regenerate the session id and delete the file.
//This is for security
session_regenerate_id(TRUE);

/**
 * Include the config file
 */
require_once('config/config.php');

/**
 * Require api functions
 */
require_once('libraries/japi.php');

/**
 * Include the router and let the magic happens
 */
require_once(SERVER_ROOT . 'controllers/' . 'router.php');