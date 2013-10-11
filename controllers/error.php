<?php

/**
 * @file
 * Handles error
 */

class Error_Controller {
  public function getErrorPage() {
    $view = new View_Model('error');
    return $view->render(FALSE);
  }
}