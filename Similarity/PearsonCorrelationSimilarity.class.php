<?php

require_once(dirname(__FILE__) . '/../Interfaces/UserSimilarity.interface.php');

class PearsonCorrelationSimilarity implements UserSimilarity {
  private $_dataSet;
  
  public function __construct(Dataset $dataset) {
    $this->_dataSet = $dataset;
  }
  
  public function userSimilarity($userId1, $userId2) {
    $userRatings1 = $this->_dataSet->getUserRatingsArray($userId1);
    $userRatings2 = $this->_dataSet->getUserRatingsArray($userId2);
    
    $mutuallyRated = array_intersect(array_keys($userRatings1),
                                     array_keys($userRatings2));

    $length = count($mutuallyRated);
    if (!$length) return 0;
    
    $sum1 = $sum2 = $sum1Sq = $sum2Sq = $sumPrd = 0;
    
    foreach ($mutuallyRated as $item) {
      $sum1   += $userRatings1[$item];
      $sum2   += $userRatings2[$item];
  
      $sum1Sq += pow($userRatings1[$item], 2);
      $sum2Sq += pow($userRatings2[$item], 2);
      
      $sumPrd += $userRatings1[$item] * $userRatings2[$item];
    }
    
    $num = $sumPrd - ($sum1 * $sum2 / $length);
    
    $den = sqrt(($sum1Sq - pow($sum1, 2) / $length)
                * ($sum2Sq - pow($sum2, 2) / $length));
    if (!$den) return 0;
    
    return $num / $den;
  }
}