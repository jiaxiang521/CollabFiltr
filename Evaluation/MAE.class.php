<?php

require_once(dirname(__FILE__) . '/Interfaces/Evaluation.class.php');
require_once(dirname(__FILE__) . '/Interfaces/Recommender.class.php');

class MAE implements Evaluation {
  public static function calculate(Recommender $recommender, $sample) {
    $sum = $i = 0;

    foreach ($sample as $rating) {
      $i++;
    
      $estimate = $recommender->estimateRating($rating->getUserId(), $rating->getItemId());
      $sum += abs($estimate - $rating->getRating());
    }
    
    return $sum / count($sample);
  }
}
