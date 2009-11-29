<?php

require_once(dirname(__FILE__) . '/../settings.php');
require_once(dirname(__FILE__) . '/../../DataSet/FileDataSet.class.php');

define('DATA_FILE', dirname(__FILE__) . '/../../../100k.data');

class MovieLens100kTest extends CollabFiltrTest {
  private $_dataSet;

  public function setUp() {
    $this->_dataSet = new FileDataSet(DATA_FILE);
  }
  
  public function testUserCount() {
    $this->assertEquals($this->_dataSet->getNumUsers(), 943);
  }
  
  public function testItemCount() {
    $this->assertEquals($this->_dataSet->getNumItems(), 1682);
  }
}