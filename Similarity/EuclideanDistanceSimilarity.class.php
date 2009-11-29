<?php

require_once(dirname(__FILE__) . '/../Interfaces/UserSimilarity.interface.php');

class EuclideanDistanceSimilarity implements UserSimilarity {
  private $_dataSet;
  
  public function __construct(Dataset $dataset) {
    $this->_dataSet = $dataset;
  }
  
  public function userSimilarity($userId1, $userId2) {
    $userRatings1 = $this->_dataSet->getUserRatingsArray($userId1);
    $userRatings2 = $this->_dataSet->getUserRatingsArray($userId2);
    
    $mutuallyRated = array_intersect(array_keys($userRatings1),
                                     array_keys($userRatings2));

    if (!count($mutuallyRated)) return 0;
    
    $sumOfSquares = 0;
    
    foreach ($mutuallyRated as $item)
      $sumOfSquares += pow($userRatings1[$item] - $userRatings2[$item], 2);
 
    return 1 / ($sumOfSquares + 1);
  }
}