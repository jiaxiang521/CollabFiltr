<?php

require_once(dirname(__FILE__) . '/../settings.php');
require_once(dirname(__FILE__) . '/../../Model/User.class.php');
require_once(dirname(__FILE__) . '/../../Model/Item.class.php');
require_once(dirname(__FILE__) . '/../../Model/GenericRating.class.php');

class ModelTest extends CollabFiltrTest {
  private $_dataSet;

  public function setUp() {
    $this->_dataSet = new MockDataSet();
  }
  
  public function testUser() {
    $user = $this->_dataSet->getUser('Jack Matthews');
    
    $this->assertEquals($user->getId(), 'Jack Matthews');
  }
  
  public function testRatingsUserId() {
    $rating = new GenericRating('Gene Seymour', 'Superman Returns', 5.0);
    
    $this->assertEquals($rating->getUserId(), 'Gene Seymour');
  }
  
  public function testRatingsItemId() {
    $rating = new GenericRating('Gene Seymour', 'Superman Returns', 5.0);
    
    $this->assertEquals($rating->getItemId(), 'Superman Returns');
  }
  
  public function testRatingsRating() {
    $rating = new GenericRating('Gene Seymour', 'Superman Returns', 5.0);
    
    $this->assertEquals($rating->getRating(),  5.0);
  }
  
  public function testUserRatings() {
    $user = $this->_dataSet->getUser('Jack Matthews');
    
    $userRatings = $this->_dataSet->getUserRatingsArray('Jack Matthews');
    $testRatings = $user->getRatings();
    
    // testRatings in same format as userRatings for comparison
    $cmpRatings = array();
    
    foreach ($user->getRatings() as $rating)
      $cmpRatings[$rating->getItemId()] = $rating->getRating();
    
    $this->assertEquals($cmpRatings, $userRatings);
  }
}