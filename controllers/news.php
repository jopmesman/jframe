<?php

/**
 * @file This file contains the News_Controller class
 * It's a general class to interact with the news_model
 */

/**
 * Table definition
 *
 * CREATE TABLE IF NOT EXISTS `news` (
 * `news_id` int(11) NOT NULL AUTO_INCREMENT,
 * `news_title` varchar(255) NOT NULL,
 * `news_message` text NOT NULL,
 * `news_published` tinyint(4) NOT NULL,
 * `news_admin_seen` tinyint(4) NOT NULL,
 * `news_date` timestamp NOT NULL,
 * PRIMARY KEY (`news_id`)
 * ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
 */

/**
 * Class News_Controller
 */
class News_Controller {
  private $newsModel;
  private $usercontroller;

  /**
   * Constructor
   */
  public function __construct() {
    $this->newsModel = new News_model;
    $this->usercontroller = new User_controller;
  }

  /**
   * Main news function
   * Just show all newsitems
   * @param optional integer $news_id
   * @return string Generated newsitem list page
   */
  public function main($news_id = NULL) {
    //we are going to watch al newsitems
    $content = $this->showAllNews($news_id);

    //Instanciate the view to create a nice view
    $newsview = new View_model('news');
    $newsview->assign('content', $content);
    $return = $newsview->render(FALSE);

    return $return;
  }

  /**
   * Get generated html of all newsitems
   * If the user is logged in (admin) the admin_seen will be updated.
   *
   * @param optional integer $news_id
   * @return string Generated news items
   */
  private function showAllNews($news_id = NULL) {
    //Standard output
    $return = 'No news Found';
    $newsitemview = new View_model('newsitem');

    $published = TRUE;
    if ($this->usercontroller->userLoggedIn()){
      $published = FALSE;
      $loggedin = TRUE;
    }
    $newsitems = $this->newsModel->getAllNews($published, $news_id);

    if($loggedin) {
      //If the user is logged in all items will be updated to seen
      $this->newsModel->newsSetAllSeen();
    }

    //Render if there are more then zero items
    if (count($newsitems) > 0) {
      $newsitemview->assign('loggedin', $loggedin);
      $newsitemview->assign('news', $newsitems);
      $return = $newsitemview->render(FALSE);
    }

    return $return;
  }

  /**
   * Count unseen items
   * Future feature
   * @return integer
   */
  public function countUnseenNewsItems(){
    return $this->newsModel->countUnseenNewsItems();
  }

  /**
   * Publish or unpublish an NewsItem
   * @param string $action
   * @param integer $news_id
   */
  public function pubunpubNewsItem($action, $news_id = NULL) {
    $this->newsModel->pubunpub($action, $news_id);
    $message = sprintf('Newsitems is %s', ($action == 'pub') ? 'published' : 'unpublished');
    setSuccessMessage($message);
    gotoPage('news');
  }

  /**
   * Delete an newsitem
   * @param integer $news_id
   */
  public function deleteNewsItem($news_id) {
    $this->newsModel->deleteNewsItem($news_id);
    setSuccessMessage('Newsitem deleted');
    gotoPage('news');
  }

  /**
   * Add or edit an newsitems
   *
   * @param string $action
   * @param integer $news_id
   * @return string generated add or edit page
   */
  public function addEditNews($action, $news_id = NULL) {
    $return = '';
    $template = 'news_edit';
    $newsview = new View_Model($template);

    //Assing an loggedin variable to the view
    if($this->usercontroller->userLoggedIn()) {
      $loggedin = TRUE;
      $newsview->assign('loggedin', $loggedin);
    }


    $page = '';
    switch ($action) {
      case 'add':
        $page = 'news/add';
        break;
      case 'edit':
        if ($loggedin) {
          if (!is_null($news_id) and is_numeric($news_id)) {
            $newsItem = $this->newsModel->getNewsItem($news_id);
            if (isset($newsItem['news_id'])) {
              foreach ($newsItem as $key => $value) {
                $newsview->assign($key, $value);
              }
              $page = 'news/edit/' . $newsItem['news_id'];
              //Set the formId to check at a formsubmission if a user don't mess
              setFormid($newsItem['news_id']);
            }
            else{
              //error
              setErrorMessage('NewsItem not found');
            }
          }
          else{
            //error
            setErrorMessage('Action is not allowed');
          }
        }
        else{
          setErrorMessage("Your not allowed to access this page");
          return '';
        }
        break;
      default:
        setErrorMessage('Action is not allowed');
        //STOP and go to home!
        gotoPage('home');
        break;
    }

    $newsview->assign('page', $page);
    //Check if the user clicked the  button
    if (isset($_POST['addedit_news_submit']) and
        $_POST['addedit_news_submit'] == 'Save' ) {
      //User clicked
      if ($action == 'add' or ($action == 'edit'and $news_id == getFormid())){
        //eventualy do validation
        if ($this->validateNewsForm($_POST) === FALSE ) {
          //Validation fails
          $newsview->assign('news_title', $_POST['news_title']);
          $newsview->assign('news_message', $_POST['news_message']);
          $newsview->assign('news_published', $_POST['news_pubunpub']);
          $return = $newsview->render(FALSE);
        }
        else{
          //Validation succeded
          $this->submitNewsForm($action, $_POST, (isset($news_id)) ? $news_id : NULL);
          $message = "Newsitem is saved. %s";
          $extramessage = '';
          if ($action == 'add' and $loggedin == FALSE){
            $extramessage = 'The admin of the site will publish it as soon as possible.';
          }
          setSuccessMessage(sprintf($message, $extramessage));
          gotoPage('news');
        }
      }
      else{
       setErrorMessage('You are not allowed to do this action22');
      }
    }
    else{
      //Not clicked. Just render the page
      $return = $newsview->render(FALSE);
    }

    return $return;
  }

  /**
   * Valiodate the incoming post variables
   * Return FALSE if failed
   * @param array $post
   * @return boolean
   */
  private function validateNewsForm($post){
    $return = TRUE;
    if (strlen($post['news_title']) == 0) {
      setErrorMessage("News title is required");
      $return = FALSE;
    }
    if (strlen($post['news_message']) == 0) {
      setErrorMessage("News message is required");
      $return = FALSE;
    }

    //Just checking if somebody is try to hack me.
    if ($this->usercontroller->userLoggedIn()) {
      if ($post['news_pubunpub'] !== '0' and $post['news_pubunpub'] !== '1') {
        setErrorMessage('Unknown input');
        $return = FALSE;
      }
    }

    return $return;
  }

  /**
   * Subm,it the news item
   * @param string $action
   * @param array $post
   * @param integer $id
   */
  private function submitNewsForm($action, $post, $id = NULL) {
    if ($action == 'add') {
      $this->newsModel->insertNewsItem($post);
    }
    elseif ($action == 'edit' and !is_null($id)) {
      $this->newsModel->updateNewsItem($post, $id);
    }
  }

  /**
   * Try to add some json.
   *
   * @param string $action
   * @param integer $news_id
   *
   *
   */
  public function js_pubunpubNewsItem($action, $news_id) {

  }

  /**
   * Function to generate blocks
   */
  public function newsBlock() {
    $usercontroller = new User_Controller();
    $newscontroller = new News_controller();

    $blockView = new View_Model('newsblock');
    if ($usercontroller->userLoggedin()) {
      $blockView->assign('loggedin', TRUE);
      $blockView->assign('unseen', $newscontroller->countUnseenNewsItems());
    }

    return $blockView->render(FALSE);
  }
}