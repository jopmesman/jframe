<?php

/**
 * Generial file to have function to use everywhere
 */

/**
 * Set an error
 * @param string $message
 */
function setErrorMessage($message) {
  $_SESSION[SESSNAME]['messages']['error'][] = $message;
}

/**
 * Get all error messages and unset them all
 * @return array
 */
function getErrorMessages() {
  $errors = $_SESSION[SESSNAME]['messages']['error'];
  unset($_SESSION[SESSNAME]['messages']['error']);
  return $errors;
}

/**
 * Set an success message
 * @param string $message
 */
function setSuccessMessage($message) {
  $_SESSION[SESSNAME]['messages']['success'][] = $message;
}

/**
 * Return all success messages and unset them
 * @return type
 */
function getSuccessMessages() {
  $success = $_SESSION[SESSNAME]['messages']['success'];
  unset($_SESSION[SESSNAME]['messages']['success']);
  return $success;
}

/**
 * Generic function to do a redirect
 * @param type $path
 */
function gotoPage($path) {
  header('location: ?page=' . $path);
  exit();
}

/**
 * To check if a user is allready loaded the form
 * Set the id
 * @param integer $id
 */
function setFormid($id) {
  $_SESSION[SESSNAME]['formid'] = $id;
}

/**
 * If you would like to check if the user allready loaded the form,
 * get the id and do a check.
 * @return integer
 */
function getFormid() {
  return $_SESSION[SESSNAME]['formid'];
}

/**
 * Determine if the user is loggedin
 */
function userLoggedIn() {
  static $cache = NULL;
  if (!is_null($cache)) {
    return $cache;
  }
  else{
    $userController = new User_Controller();
    $cache = $userController->userLoggedIn();
    return $cache;
  }
}
