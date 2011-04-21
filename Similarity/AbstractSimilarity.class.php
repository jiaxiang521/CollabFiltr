<?php

require_once(dirname(__FILE__) . '/../Interfaces/UserSimilarity.interface.php');
require_once(dirname(__FILE__) . '/../Interfaces/ItemSimilarity.interface.php');

abstract class AbstractSimilarity implements UserSimilarity, ItemSimilarity {
  protected $_dataSet;
  
  public function __construct(Dataset $dataset) {
    $this->_dataSet = $dataset;
  }
  
  public function userSimilarity($userId1, $userId2) {
    $userRatings1 = $this->_dataSet->getUserRatingsArray($userId1);
    $userRatings2 = $this->_dataSet->getUserRatingsArray($userId2);
    
    return $this->_similarity($userRatings1, $userRatings2);
  }
  
  public function itemSimilarity($itemId1, $itemId2) {
    $itemRatings1 = $this->_dataSet->getItemRatingsArray($itemId1);
    $itemRatings2 = $this->_dataSet->getItemRatingsArray($itemId2);
    
    return $this->_similarity($itemRatings1, $itemRatings2);
  }
  
  abstract protected function _similarity($ratings1, $ratings2);
}