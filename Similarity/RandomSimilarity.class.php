<?php

require_once(dirname(__FILE__) . '/../Interfaces/UserSimilarity.interface.php');
require_once(dirname(__FILE__) . '/../Interfaces/ItemSimilarity.interface.php');

class RandomSimilarity implements UserSimilarity, ItemSimilarity {
  public function userSimilarity($userId1, $userId2) {
    return $this->_similarity();
  }
  
  public function itemSimilarity($itemId1, $itemId2) {
    return $this->_similarity();
  }

  private function _similarity() {
    return (float)mt_rand() / (float)getrandmax();
  }
}