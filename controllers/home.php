<?php
/**
 * @file The Home_Controller file
 * General class for the home_controller
 */

/**
 * Class Home_controller
 */
class Home_Controller{
  private $template = 'home';
  private $title = 'Home';

  /**
   *
   * @param array $vars
   * @return string Generated home content
   */
  public function main($vars = array()) {
		//create a new view and pass it our template
		$view = new View_Model($this->template);
    $return = $view->render(FALSE);
    return $return;
  }
}