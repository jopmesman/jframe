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
    $blocks = $this->blocksConfig();

    foreach ($blocks as $block) {
      $blockController = new $block['controller']();
      $return[$block['region']][] = call_user_method($block['function'], $blockController);
    }

    return $return;
  }

  private function blocksConfig() {
    return array(
      array(
        'title' => 'News',
        'controller' => 'News_Controller',
        'function' => 'newsBlock',
        'region' => 'left',
      ),
      array(
        'title' => 'User',
        'controller' => 'User_controller',
        'function' => 'createLoginBlock',
        'region' => 'left',
      )
    );
  }
}