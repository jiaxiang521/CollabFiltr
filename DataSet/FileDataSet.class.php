<?php

require_once(dirname(__FILE__) . '/../interfaces/DataSet.interface.php');
require_once(dirname(__FILE__) . '/GenericDataSet.class.php');

class FileDataSet implements DataSet {
  private $_dataSet;

  public function __construct($file) {
    $fh = fopen($file, 'rb');
    if (!$fh) throw new RuntimeException('Could not open file');

    $builder = new GenericDataSetBuilder();

    while (!feof($fh))
      $this->loadLine(fgets($fh), $builder);

    fclose($fh);

    $this->_dataSet = $builder->build();
  }

  // put line from file into $_data
  protected function loadLine($line, $builder) {
    if (!$line) return;

    list($userId, $itemId, $rating) = explode("\t", $line);
    $builder->loadLine($userId, $itemId, $rating);
  }

  public function getUserIds() { return $this->_dataSet->getUserIds(); }
  public function getItemIds() { return $this->_dataSet->getItemIds(); }

  public function getNumUsers() { return $this->_dataSet->getNumUsers(); }
  public function getNumItems() { return $this->_dataSet->getNumItems(); }

  public function getUser($userId) { return $this->_dataSet->getUser($userId); }
  public function getItem($itemId) { return $this->_dataSet->getItem($itemId); }

  public function getUserRatingsArray($user) { return $this->_dataSet->getUserRatingsArray($user); }
  public function getItemRatingsArray($item) { return $this->_dataSet->getItemRatingsArray($item); }
}