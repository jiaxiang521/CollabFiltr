<?php

require_once(dirname(__FILE__) . '/../Interfaces/Recommender.interface.php');

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
    $userRatings  = $this->_dataSet->getUserRatingsArray($userId);
    
    $totalScores   = array();
    $sumSimilarity = array();
    $rankings      = array();
    
    foreach ($neighbours as $neighbour => $similarity) {
      $neighbourRatings = $this->_dataSet->getUserRatingsArray($neighbour);
      
      foreach ($neighbourRatings as $itemId => $rating) {
        // only score items user has not seen
        
        if (isset($userRatings[$itemId]) && $userRatings[$itemId])
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
    // use actual rating if one
    $user   = new User($userId, $this->_dataSet->getUserRatingsArray($userId));
    $rating = $user->getRating($itemId);
    if ($rating) return $rating;
    
    $neighbours     = $this->_neighbourhood->userNeighbourhood($userId);
    $userSimilarity = $this->_neighbourhood->getSimilarity();  
      
    $averageRating = $user->getAverageRating($this->_dataSet);
    $similaritySum = 0;
    $estimate      = 0;
    
    foreach ($neighbours as $neighbour => $similarity) {
      if ($neighbour == $userId) continue;
      $user = new User($neighbour, $this->_dataSet->getUserRatingsArray($neighbour));

      $neighbourRating = $user->getRating($itemId);
      if ($neighbourRating == null) continue;

      // similarity in range [0,2] (better for weighting than [-1,1])
      $similarity = $userSimilarity->userSimilarity($userId, $neighbour) + 1;
      
      $estimate      += ($similarity * $neighbourRating);
      $similaritySum += $similarity;
    }
    
    $rating = $similaritySum ? ($estimate / $similaritySum) : $averageRating;
    
    if (is_float($this->_dataSet->getRatingMin()))
      return $rating;
    
    return round($rating);
  }
}