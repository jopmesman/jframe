<?php
/**
 * @file General user_controller class
 */


/**
 * Class User_controller
 */
class User_controller {
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
  public function createLoginBlock($config) {
    //check wether a user is loggin
    $data['loggedin'] = FALSE;

    //Check if the  user is already logged in
    if ($user = $this->userLoggedin()) {
      //the user is logged in
      $data['loggedin'] = TRUE;
      $data['username'] = $user['user_name'];
      $data['user_id'] = $user['user_id'];
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

    $data['title'] = $config['title'];

    //Generate the html
    $userview = new View_Model('user_block');
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

  public function editUser($user_id) {
    $user = getUser();
    if (userLoggedIn() and $user_id == $user['user_id']) {
      $userEditView = new View_Model('user_edit');
      $userEditView->assign('page', 'user/edit/' . $user_id);
    }
    else{
      setErrorMessage('You are not allowed to acces this page');
      gotoPage('home');
    }

    return $userEditView->render(FALSE);
  }
}