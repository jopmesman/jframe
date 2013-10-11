<?php
session_start();

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
require_once(SERVER_ROOT . '/controllers/' . 'router.php');