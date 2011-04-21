<?php

require_once(dirname(__FILE__) . '/../Interfaces/UserReputation.interface.php');

class SimpleReputation implements UserReputation {
  private $_dataSet;
  private $_similarity;

  public function __construct(Dataset $dataset, UserSimilariy $similarity) {
    $this->_dataSet    = $dataset;
    $this->_similarity = $similarity;
  }
  
  public function userReputation($userId) {
    $this->userBias($userId, $userId);
  }
  
  public function userBias($userId1, $userId2) {
    $reputation = $this->_dataSet->reputation($userId2);
    $similarity = $this->_similarity->userSimilarity($userId1, $userId2);
    
    return $reputation * $similarity;
  }
}