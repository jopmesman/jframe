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
      try {
        $blockController = new $block['controller']();
        $return[$block['region']][] = array(
          'content' => call_user_method($block['function'], $blockController, $block),
          'sort' => $block['sort'],
        );
      } catch (Exception $exc) {
        //There is an error.
        //At this moment we can't do anything.
        //Just go on to the next block if needed
      }
    }

    //The blocks are filled.
    //Now we arrage them a bit
    if (count($return) > 0) {
      foreach ($return as $keyregion => $value) {
        uasort($return[$keyregion], array($this, 'sortBlock'));
      }
    }

    return $return;
  }

  private function sortBlock($a, $b) {
    if ($a['sort'] == $b['sort']) {
        return 0;
    }

    return ($a['sort'] < $b['sort']) ? -1 : 1;
  }

  private function blocksConfig() {
    return array(
      array(
        'title' => 'News',
        'controller' => 'News_Controller',
        'function' => 'newsBlock',
        'region' => 'left',
        'sort' => 0,
      ),
      array(
        'title' => 'Login',
        'controller' => 'User_controller',
        'function' => 'createLoginBlock',
        'region' => 'left',
        'sort' => 1,
      )
    );
  }
}