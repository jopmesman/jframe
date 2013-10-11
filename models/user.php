<?php
/**
 * @file General user_model
 */

/**
 *Class User_model
 */
class User_Model extends Model_Model {

  public function __construct() {
    //let's make a connection
    $this->db = new Mysql_Driver(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
  }

  /**
   * Check if the requested user occured in the db
   * @param string $username
   * @param string $password
   * @return boolean
   */
  public function checkLogin($username, $password) {
    //Concat the salt coliumn to the password.
    //Every user has a different salt.
    $sql = "SELECT user_id, user_name FROM user WHERE user_name = '%s' and user_password = md5(CONCAT('%s', user_salt ))";

    $result = $this->db->DoQueryAsArray($sql, array($username, $password), TRUE);
    if (isset($result['user_id'] ) and isset($result['user_name'])) {
      return $result;
    }

    return FALSE;
  }
}