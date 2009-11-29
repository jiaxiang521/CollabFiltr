<?php

require_once(dirname(__FILE__) . '/../interfaces/DataSet.interface.php');
require_once(dirname(__FILE__) . '/GenericDataSet.class.php');

class FileDataSet implements DataSet {
  private $_dataSet;

  public function __construct($file) {
    $fh = fopen($file, 'rb');
    if (!$fh) throw new RuntimeException('Could not open file');

    $builder = new GenericDataSetBuilder();

    // add each line at a time to builder
    // (using a builder as GenericDataSet is immutable)
    while (!feof($fh) && $line = fgets($fh)) {
      list($userId, $itemId, $rating) = explode("\t", $line);
      
      $builder->loadLine($userId, $itemId, $rating);
    }

    fclose($fh);

    $this->_dataSet = $builder->build();
  }

  public function getUserIds() { return $this->_dataSet->getUserIds(); }
  public function getItemIds() { return $this->_dataSet->getItemIds(); }

  public function getNumUsers() { return $this->_dataSet->getNumUsers(); }
  public function getNumItems() { return $this->_dataSet->getNumItems(); }
  
  public function isUser($userId) { return $this->_dataSet->isUser($userId); }
  public function isItem($itemId) { return $this->_dataSet->isItem($itemId); }

  public function getUser($userId) { return $this->_dataSet->getUser($userId); }
  public function getItem($itemId) { return $this->_dataSet->getItem($itemId); }

  public function getUserRatingsArray($user) { return $this->_dataSet->getUserRatingsArray($user); }
  public function getItemRatingsArray($item) { return $this->_dataSet->getItemRatingsArray($item); }
}