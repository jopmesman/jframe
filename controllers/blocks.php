<?php
/**
 * @file general block class
 * Here you can add blocks.
 * Add them to the return array.
 */

/**
 * Class Blocks_Controller
 */
class Blocks_Controller{

  /**
   * Generate an array of blocks.
   * Add as many oy like
   * @return array(
   */
  public function CreateBlocks() {
    $return = array();
    $usercontroller = new User_Controller();
    $newscontroller = new News_controller();

    $blockView = new View_Model('newsblock');
    if ($usercontroller->userLoggedin()) {
      $blockView->assign('loggedin', TRUE);
      $blockView->assign('unseen', $newscontroller->countUnseenNewsItems());
    }
    $return[] = $blockView->render(FALSE);

    return $return;
  }
}