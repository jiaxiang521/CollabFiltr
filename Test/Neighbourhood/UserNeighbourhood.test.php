<?php

require_once(dirname(__FILE__) . '/../settings.php');
require_once(dirname(__FILE__) . '/../../Neighbourhood/UserNeighbourhoodNN.class.php');

class UserNeighbourhoodTest extends CollabFiltrTest {
  private $_dataSet;

  public function setUp() {
    $this->_dataSet = new MockDataSet();
  }
  
  public function testNN() {
    $userNeighbouurNN = new UserNeighbourhoodNN($this->_dataSet, 3);
    $neighbourhood    = $userNeighbouurNN->userNeighbourhood('Toby');

    $top3 = array('Lisa Rose', 'Mick LaSalle', 'Claudia Puig');
    $this->assertEquals(array_keys($neighbourhood), $top3);
  }
}