<?php

require_once(dirname(__FILE__) . '/../settings.php');
require_once(dirname(__FILE__) . '/../Mock/MockDataSet.class.php');
require_once(dirname(__FILE__) . '/../Mock/MockSimilarity.class.php');

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
    $euclidean  = new EuclideanDistanceSimilarity($this->_dataSet);
    $similarity = $euclidean->userSimilarity('Lisa Rose', 'Gene Seymour');

    $this->assertEquals(round($similarity, 6), 0.148148);
  }
}