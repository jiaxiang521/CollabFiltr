<?php

require_once(dirname(__FILE__) . '/../interfaces/Recommender.interface.php');

class ItemBasedRecommender implements Recommender {
  private $_dataSet;
  private $_similarity;
  
  public function __construct(DataSet $dataSet, ItemSimilarity $similarity) {
    $this->_dataSet    = $dataSet;
    $this->_similarity = $similarity;
  }
  
  public function recommend($userId, $num) {
    if (!$this->_dataSet->isItem($userId))
      throw new InvalidArgumentException('InvalidUser ID');
    if (!is_numeric($num) || $num < 0)
      throw new InvalidArgumentException('Invalid number of recommendations');
      
    $recommendItems = array();
    $possibleItems  = array_diff($this->_dataSet->getItemIds(),
                                 array_keys($this->_dataSet->getUserRatingsArray($userId)));
    
    foreach ($possibleItems as $itemId) {
      $estimate = $this->estimateRating($userId, $itemId);
      
      $recommendItems[$itemId] = $estimate;
      unset($possibleItems[$itemId]);
    }
    
    arsort($recommendItems);
    
    // return top n (key = itemId, value = score)
    return array_slice($rankings, 0, $num, true);
  }
  
  public function estimateRating($userId, $itemId) {
    $ratings = $this->_dataSet->getUserRatingsArray($userId);
    
    $averageRating = $user->getAverageRating($this->_dataSet);
    $similaritySum = 0;
    $estimate      = 0; 

    foreach ($ratings as $rated => $rating) {
      $similarity = $this->_similarity->itemSimilarity($rated, $itemId) + 1;
      
      $estimate      += ($similarity * $rating);
      $similaritySum += $similarity;
    }
    
    if ($similaritySum)
      return ($estimate / $similaritySum);
      
    $item = new Item($itemId, $this->_dataSet->getItemRatingsArray($itemId));  
    return $item->getAverageRating;
  }
}