<?php
/**
 * @file General news_model
 */

/**
 * Class News_model
 */
class News_Model {
  private $db = NULL;
  private $usercontroller = NULL;

  /**
   * Instanciate some classes
   */
  public function __construct() {
    //let's make a connection
    $this->db = new Mysql_Driver(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    $this->usercontroller = new User_Controller();
  }

  /**
   * Get all News
   * @param boolean published
   *
   * @param optional integer $news_id
   * @return array $newsItems
   */
  public function getAllNews($published = TRUE, $news_id = NULL) {

    $query = "SELECT * FROM news";
    if ($published) {
      //Only get the published news
      $where[] = 'news_published = 1';
    }
    if (!is_null($news_id)) {
      $where[] = 'news_id = %d';
      $values[] = $news_id;
    }

    if ($where) {
      $query .= ' WHERE ' . implode(' AND ', $where);
    }

    $query .= " ORDER BY news_date DESC";
    $newsitems = $this->db->DoQueryAsArray($query, $values);

    return $newsitems;
  }

  /**
   * Count all unseen items
   */
  public function countUnseenNewsItems() {
    $query = "SELECT count(news_id) FROM news WHERE news_admin_seen = 0";
    return $this->db->DoQueryAsSingleValue($query);
  }

  /**
   * Get all news items that have been seen by the admin
   */
  public function newsSetAllSeen() {
    $this->db->DoQuery($this->db->BuildUpdateQuery('news',
      array('news_admin_seen' => 1 )));
  }

  /**
   * Publish or unpublish the newsitem
   *
   * @param strinf $action
   * @param integer $news_id
   */
  public function pubunpub($action, $news_id) {
    $values[] = ($action == 'pub') ? 1:0;
    if (isset($news_id) and is_numeric($news_id)) {
      $values[] = $news_id;
      $query = $this->db->BuildUpdateQuery('news', array('news_published' => '%d'), 'news_id = %d');
      $this->db->DoQuery($query, $values);
    }
  }

  /**
   * Get the newsitem by news_id
   * @param integer $id
   * @return array
   */
  public function getNewsItem($id) {
    $query = "SELECT * FROM news WHERE news_id = %d";
    $values[] = $id;

    $result = $this->db->DoQueryAsArray($query, $values, TRUE);
    return $result;
  }

  /**
   * Insrt a newsitem in the database
   * @param array $post
   */
  public function insertNewsItem($post) {
    //Build the query
    $query = $this->db->BuildInsertQuery('news', array(
      'news_title' => "%s",
      'news_message' => '%s',
      'news_published' => '%d',
      'news_admin_seen' => '%d',
      'news_date' => '%s',
    ));

    //add values
    $values = array(
      $post['news_title'],
      $post['news_message'],
      ($this->usercontroller->userLoggedIn()) ? $post['news_pubunpub'] : 0,
      ($this->usercontroller->userLoggedIn()) ? 1 : 0,
      date("Y-m-d H:i:s"),
    );

    //Run the query
    $this->db->Doquery($query, $values);
  }

  /**
   * Update a newsitem in the database
   *
   * @param array $post
   * @param integer $news_id
   */
  public function updateNewsItem($post, $news_id) {
    //Build the query
    $query = $this->db->BuildUpdateQuery('news', array(
       'news_title' => "%s",
       'news_message' => '%s',
       'news_published' => '%d',
       'news_admin_seen' => '%d',
       ),
       'news_id = %d'
    );
    //add values
    $values = array(
      $post['news_title'],
      $post['news_message'],
      ($this->usercontroller->userLoggedIn()) ? $post['news_pubunpub'] : 0,
      ($this->usercontroller->userLoggedIn()) ? 1 : 0,
      $news_id,
    );
    //Run the query
    $this->db->DoQuery($query, $values);
  }

  /**
   * Delete an newsitem
   * @param integer $id
   */
  public function deleteNewsItem($id){
    $values[] = $id;
    $query = "DELETE FROM news WHERE news_id = %d";
    $this->db->DoQuery($query, $values);
  }
}