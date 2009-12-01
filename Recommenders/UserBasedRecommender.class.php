<?php

require_once(dirname(__FILE__) . '/../interfaces/Recommender.interface.php');

class UserBasedRecommender implements Recommender {
  private $_dataSet;
  private $_neighbourhood;
  
  public function __construct(DataSet $dataSet, UserNeighbourhood $neighbourhood) {
    $this->_dataSet       = $dataSet;
    $this->_neighbourhood = $neighbourhood;
  }

  public function recommend($userId, $num) {
    if (!$this->_dataSet->isUser($userId))
      throw new InvalidArgumentException('Invalid User ID');
    if (!is_numeric($num) || $num < 0)
      throw new InvalidArgumentException('Invalid number of recommendations');
  
    $neighbours   = $this->_neighbourhood->userNeighbourhood($userId);
    $userRatings1 = $this->_dataSet->getUserRatingsArray($userId);
    
    $totalScores   = array();
    $sumSimilarity = array();
    $rankings      = array();

    foreach ($neighbours as $neighbour => $similarity) {
      $userRatings2 = $this->_dataSet->getUserRatingsArray($neighbour);
      
      foreach ($userRatings2 as $itemId => $rating) {
        // only score items user has not seen
        if (isset($userRatings1[$itemId]) && $userRatings1[$itemId])
          continue;
        
        if (!isset($totalScores[$itemId]))
          $totalScores[$itemId] = 0;
        if (!isset($sumSimilarity[$itemId]))
          $sumSimilarity[$itemId] = 0;
          
        $totalScores[$itemId]   += $rating * $similarity;
        $sumSimilarity[$itemId] += $similarity;
      }
    }
      
    foreach ($totalScores as $itemId => $score)
      $rankings[$itemId] = $score / $sumSimilarity[$itemId];
      
    arsort($rankings);
    
    // return top n (key = itemId, value = score)
    return array_slice($rankings, 0, $num, true);
  }
  
  public function estimateRating($userId, $itemId) {
    // todo
  }
}