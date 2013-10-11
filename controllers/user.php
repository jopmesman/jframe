<?php
/**
 * @file General user_controller class
 */

/**
 * user table
 * CREATE TABLE IF NOT EXISTS `user` (
 * `user_id` int(11) NOT NULL AUTO_INCREMENT,
 * `user_name` varchar(255) NOT NULL,
 * `user_password` varchar(255) NOT NULL,
 * `user_salt` varchar(255) NOT NULL,
 * PRIMARY KEY (`user_id`)
 * ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
 *
 * INSERT INTO  `jframe`.`user` (
 * `user_id` ,
 * `user_name` ,
 * `user_password` ,
 * `user_salt`
 * )
 * VALUES (
 * NULL ,  'admin',  'd6c811c7dd8c6945e85f73f418b539e6',  'AYN'
 * );
 *
 * Now you can use the login admin/admin
 */

/**
 * Class User_controller
 */
class User_controller {
  private $template = 'user';
  private $actionoptions = array(
    'logout',
  );

  /**
   * Take action to the actionoption
   * @param string $actionoption
   */
  public function main($actionoption) {
    if( in_array($actionoption, $this->actionoptions)) {
      //do something
      switch ($actionoption) {
        case 'logout':
          //Unset the SESSION
          unset($_SESSION[SESSNAME]['user']);

          gotoPage('home');
          break;
      }
    }
  }

  /**
   * Create an user block to show on the page
   * If a user clicked the login button it will also be validated
   * @return generated html
   */
  public function createLoginBlock() {
    //check wether a user is loggin
    $data['loggedin'] = FALSE;

    //Check if the  user is already logged in
    if ($user = $this->userLoggedin()) {
      //the user is logged in
      $data['loggedin'] = TRUE;
      $data['username'] = $user['user_name'];
    }
    else {
      //Did the clicked the login button?
      if (isset($_POST['user_submit']) ) {
        //Somebody clicked the login button
        $usermodel = new User_Model();
        $user = $usermodel->checkLogin($_POST['username'], $_POST['password']);

        //Check if the user could be loaded.
        if ($user) {
          $_SESSION[SESSNAME]['user'] = $user;
          setSuccessMessage('Successfully logged in');
          gotoPage($_GET['page']);
        }
        else{
          //User gave wrong credentials
          setErrorMessage('Username or password is incorrect');
        }
      }
    }

    //Generate the html
    $userview = new View_Model($this->template);
    foreach ($data as $key => $value) {
      $userview->assign($key, $value);
    }

    return $userview->render(FALSE);
  }

  /**
   * General function to check if the user is logged in
   * @return boolean
   */
  public function userLoggedIn() {
    static $cache = NULL;

    if (!is_null($cache)) {
      return $cache;
    }
    else{
      if (isset($_SESSION[SESSNAME]['user']['user_id'])) {
        $cache = $_SESSION[SESSNAME]['user'];
        return $_SESSION[SESSNAME]['user'];
      }
      $chache = FALSE;
      return FALSE;
    }
  }
}