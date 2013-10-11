<?php

/**
 * @file Overall class
 *
 * All model classes should extend this class
 */

class Model_Model {
  protected $db;

  public function __construct() {
    //determine the db driver

    switch (DB_DRIVER) {
      case 'mysql':
      default:
        $this->db = new Mysql_Driver();
        break;
    }
  }
}