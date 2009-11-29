<?php

require_once(dirname(__FILE__) . '/../settings.php');
require_once(dirname(__FILE__) . '/../../Similarity/PearsonCorrelationSimilarity.class.php');
require_once(dirname(__FILE__) . '/../../Similarity/EuclideanDistanceSimilarity.class.php');

class UserSimilarityTest extends CollabFiltrTest {
  private $_dataSet;

  public function setUp() {
    $this->_dataSet = new MockDataSet();
  }
  
  public function testPearson() {
    $pearson    = new PearsonCorrelationSimilarity($this->_dataSet);
    $similarity = $pearson->userSimilarity('Lisa Rose', 'Gene Seymour');

    $this->assertEquals(round($similarity, 6), 0.396059);
  }
  
  public function testEuclidean() {
    $pearson    = new EuclideanDistanceSimilarity($this->_dataSet);
    $similarity = $pearson->userSimilarity('Lisa Rose', 'Gene Seymour');

    $this->assertEquals(round($similarity, 6), 0.148148);
  }
}

class MockDataSet implements DataSet {
  private $_users;

  public function __construct() {
    $this->_users = array('Lisa Rose' => array('Lady in the Water'  => 2.5,
                                               'Snakes on a Plane'  => 3.5,
                                               'Just My Luck'       => 3.0,
                                               'Superman Returns'   => 3.5,
                                               'You, Me and Dupree' => 2.5,
                                               'The Night Listener' => 3.0),
                                               
                          'Gene Seymour' => array('Lady in the Water'  => 3.0,
                                                  'Snakes on a Plane'  => 3.5,
                                                  'Just My Luck'       => 1.5,
                                                  'Superman Returns'   => 5.0,
                                                  'The Night Listener' => 3.0,
                                                  'You, Me and Dupree' => 3.5));
  }

  public function getUserIds()  { }
  public function getItemIds()  { }
  public function getNumUsers() { }
  public function getNumItems() { }
  public function isUser($a)    { }
  public function isItem($a)    { }
  public function getUser($a)   { }
  public function getItem($a)   { }
  public function getItemRatingsArray($a) { }
  
  public function getUserRatingsArray($user) { return $this->_users[$user]; }
}