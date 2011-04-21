<?php

require_once(dirname(__FILE__) . '/../settings.php');
require_once(dirname(__FILE__) . '/../../Recommenders/UserBasedRecommender.class.php');

require_once(dirname(__FILE__) . '/../Mock/MockDataSet.class.php');
require_once(dirname(__FILE__) . '/../Mock/MockSimilarity.class.php');

require_once(dirname(__FILE__) . '/../../Neighbourhood/UserNeighbourhoodKNN.class.php');

class UserBasedRecommenderTest extends CollabFiltrTest {
  private $_dataSet;

  public function setUp() {
    $this->_dataSet = new MockDataSet();
  }
  
  public function testPearsonNN() {
    $similarity = new MockSimilarity('pearson');

    $userKNN     = new UserNeighbourhoodKNN($this->_dataSet, 30, $similarity);
    $recommender = new UserBasedRecommender($this->_dataSet, $userKNN);
    
    $recommendations = $recommender->recommend('Toby', 3);
    
    // truncate to 6dp
    $recommendations = array_map(create_function('$n', 'return substr($n, 0, 8);'),
                                 $recommendations);
                                 
    $top3 = array('The Night Listener' => 3.347789,
                  'Lady in the Water'  => 2.832549,
                  'Just My Luck'       => 2.530980);
    
    $this->assertEquals($recommendations, $top3);
  }
  
  public function testEuclideanNN() {
    $similarity = new MockSimilarity('euclidean');

    $userKNN     = new UserNeighbourhoodKNN($this->_dataSet, 30, $similarity);
    $recommender = new UserBasedRecommender($this->_dataSet, $userKNN);
    
    $recommendations = $recommender->recommend('Toby', 3);
    
    // truncate to 6dp
    $recommendations = array_map(create_function('$n', 'return substr($n, 0, 8);'),
                                 $recommendations);
    
    $top3 = array('The Night Listener' => 3.500247,
                  'Lady in the Water'  => 2.756124,
                  'Just My Luck'       => 2.461988);

    $this->assertEquals($recommendations, $top3);
  }
}