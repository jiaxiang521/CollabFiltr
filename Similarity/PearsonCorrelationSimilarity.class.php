<?php

require_once(dirname(__FILE__) . '/AbstractSimilarity.class.php');

class PearsonCorrelationSimilarity extends AbstractSimilarity {
  protected function _similarity($ratings1, $ratings2) {
    $mutuallyRated = array_intersect(array_keys($ratings1),
                                     array_keys($ratings2));

    $length = count($mutuallyRated);
    if (!$length) return 0;
  
    $sum1 = $sum2 = $sum1Sq = $sum2Sq = $sumPrd = 0;
    foreach ($mutuallyRated as $index) {
      $sum1   += $ratings1[$index];
      $sum2   += $ratings2[$index];
  
      $sum1Sq += pow($ratings1[$index], 2);
      $sum2Sq += pow($ratings2[$index], 2);
      
      $sumPrd += $ratings1[$index] * $ratings2[$index];
    }
    
    $num = $sumPrd - ($sum1 * $sum2 / $length);
    
    $den = sqrt(($sum1Sq - pow($sum1, 2) / $length)
                * ($sum2Sq - pow($sum2, 2) / $length));
    if (!$den) return 0;
    
    return $num / $den;
  }
}