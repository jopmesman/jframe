<?php

/**
 * @file
 * In this file you can add routing options
 * Add your routing options and create your own controller to interact with it.
 */

/**
 * Class Routing_controller
 */
class Routing_controller {

  /**
   * wrapper to get the routung options
   * This wrapper's main goals is the staic cahce the options
   *
   * @staticvar null $cache
   * @return array
   */
  private function getRoutingPaths() {
    static $cache = NULL;

    if(!is_null($cache)) {
      return $cache;
    }
    else{
      $routes = $this->getRoutes();$
      $cache = $routes;
      return $routes;
    }
  }

  /**
   * keyed array to create links to the several controllers
   * @return array
   */
  private function getRoutes() {
    return array(
      'home' => array(
        'title' => 'Home',
        'controller' => 'Home_Controller',
        'function' => 'main',
        'loggedin' => FALSE,
      ),
      'news' => array(
        'title' => 'News',
        'controller' => 'News_Controller',
        'function' => 'main',
        'loggedin' => FALSE,
      ),
      'news/add' => array(
        'title' => 'Add newsitem',
        'controller' => 'News_Controller',
        'function' => 'addEditNews',
        'variables' => array(
          'add',
        ),
        'loggedin' => FALSE,
      ),
      'news/view/%' => array(
        'title' => 'Newsitem',
        'controller' => 'News_Controller',
        'function' => 'main',
        'variables' => array(
          2,
        ),
        'loggedin' => FALSE,
      ),
      'news/edit/%' => array(
        'title' => 'Edit newsitem',
        'controller' => 'News_Controller',
        'function' => 'addEditNews',
        'variables' => array(
          'edit',
          2
        ),
        'loggedin' => TRUE,
      ),
      'news/pub/%' => array(
        'title' => 'Publish a newwsitem',
        'controller' => 'News_Controller',
        'function' => 'pubunpubNewsItem',
        'variables' => array(
          'pub',
          2
        ),
        'loggedin' => TRUE,
      ),
      'news/unpub/%' => array(
        'title' => 'Publish a newwsitem',
        'controller' => 'News_Controller',
        'function' => 'pubunpubNewsItem',
        'variables' => array(
          'unpub',
          2
        ),
        'loggedin' => TRUE,
      ),
      'news/js/pub/%' => array(
        'title' => 'NR', //Not Required
        'controller' => 'News_Controller',
        'function' => 'js_pubunpubNewsItem',
        'variables' => array(
          'pub',
          3,
        ),
        'loggedin' => TRUE,
        'returntype' => 'json',
      ),
      'news/delete/%' => array(
        'title' => 'Delete a newwsitem',
        'controller' => 'News_Controller',
        'function' => 'deleteNewsItem',
        'variables' => array(
          2
        ),
        'loggedin' => TRUE,
      ),
      'user/logout' => array(
        'title' => ' Logout',
        'controller' => 'User_Controller',
        'function' => 'main',
        'loggedin' => TRUE,
        'variables' => array(
          'logout'
        ),
      ),
      'user/edit/%' => array(
        'title' => 'Edit user',
        'controller' => 'User_Controller',
        'function' => 'editUser',
        'loggedin' => TRUE,
        'variables' => array(
          2,
        ),
      ),
    );
  }

  /**
   * Use this function to create the routing option
   *
   * @param string $page
   * @return array
   */
  public function initRouting($page) {
    $routes = $this->getRoutingPaths();
    //Standard return the home;
    $return = $routes['home'];
    //For general purpose
    $explodedPage = explode('/', $page);

    //Which options do we have?
    $options = $this->createOptions($page);

    //Loop it and check if the option is a key of the router options
    //If yes, get the array and stop looping
    foreach ($options as $option) {
      if (array_key_exists($option, $routes)) {
        $return = $routes[$option];
        break;
      }
    }

    //replace the variables in the variable array
    if (is_array($return['variables']) and count($return['variables']) > 0) {
      foreach($return['variables'] as $key => $value) {
        if (is_numeric($value) and isset($explodedPage[$value])) {
          $return['variables'][$key] = $explodedPage[$value];
        }
      }
    }
    else{
      //Give it an empty variables array
      $return['variables'] = array();
    }

    //Default is html.
    //If it's not set. Add html as default
    if (!isset($return['returntype'])){
      $return['returntype'] = 'html';
    }

    return $return;
  }

  /**
   * Create an options array to match against the routeroptions array
   * @param string $page
   * @return array
   */
  private function createOptions($page) {
    $parts = explode('/', $page);

    //Bit used of the drupal menu.inc.
    $number_parts = count($parts);
    $length =  $number_parts - 1;
    $end = (1 << $number_parts) - 1;

    $masks = range(10, 1);
    // Only examine patterns that actually exist as router items (the masks).
    foreach ($masks as $i) {
      if ($i > $end) {
        // Only look at masks that are not longer than the path of interest.
        continue;
      }
      elseif ($i < (1 << $length)) {
        // We have exhausted the masks of a given length, so decrease the length.
        --$length;
      }
      $current = '';
      for ($j = $length; $j >= 0; $j--) {
        // Check the bit on the $j offset.
        if ($i & (1 << $j)) {
          // Bit one means the original value.
          $current .= $parts[$length - $j];
        }
        else {
          // Bit zero means means wildcard.
          $current .= '%';
        }
        // Unless we are at offset 0, add a slash.
        if ($j) {
          $current .= '/';
        }
      }
      $return[] = $current;
    }

    return $return;
  }
}