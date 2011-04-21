<?php

require_once(dirname(__FILE__) . '/AbstractSimilarity.class.php');

class EuclideanDistanceSimilarity extends AbstractSimilarity {
  protected function _similarity($ratings1, $ratings2) {
    $mutuallyRated = array_intersect(array_keys($ratings1),
                                     array_keys($ratings2));

    if (!count($mutuallyRated)) return 0;
    
    $sumOfSquares = 0;
    foreach ($mutuallyRated as $index)
      $sumOfSquares += pow($ratings1[$index] - $ratings2[$index], 2);
 
    return 1 / ($sumOfSquares + 1);
  }
}