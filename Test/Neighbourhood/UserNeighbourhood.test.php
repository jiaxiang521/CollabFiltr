<?php

require_once(dirname(__FILE__) . '/../settings.php');
require_once(dirname(__FILE__) . '/../Mock/MockReputation.class.php');

require_once(dirname(__FILE__) . '/../../Neighbourhood/UserNeighbourhoodKNN.class.php');
require_once(dirname(__FILE__) . '/../../Neighbourhood/UserNeighbourhoodWeightedKNN.class.php');

class UserNeighbourhoodTest extends CollabFiltrTest {
  private $_dataSet;

  public function setUp() {
    $this->_dataSet = new MockDataSet();
  }
  
  public function testNN() {
    $userNeighbourKNN = new UserNeighbourhoodKNN($this->_dataSet, 3);
    $neighbourhood    = $userNeighbourKNN->userNeighbourhood('Toby');

    $top3 = array('Lisa Rose', 'Mick LaSalle', 'Claudia Puig');
    $this->assertEquals(array_keys($neighbourhood), $top3);
  }
  
  /*public function testWNN() {
    $reputation       = new MockReputation();
    $userNeighbourKNN = new UserNeighbourhoodWeightedKNN($this->_dataSet, 3, null, $reputation);
    $neighbourhood    = $userNeighbourKNN->userNeighbourhood('Toby');

    $top3 = array('Jack Matthews', 'Lisa Rose', 'Mick LaSalle');
    $this->assertEquals(array_keys($neighbourhood), $top3);
  }*/
}