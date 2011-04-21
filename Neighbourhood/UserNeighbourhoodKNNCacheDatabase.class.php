<?php

require_once(dirname(__FILE__) . '/UserNeighbourhoodKNN.class.php');

class UserNeighbourhoodKNNCacheDatabase extends UserNeighbourhoodKNN {
  public function __construct(DataSetUserNeighbourhood $dataSet,
                              $neighbours,
                              UserSimilarity $similarity = null) {
    if (!is_integer($neighbours) || $neighbours < 0)
      throw new InvalidArgumentException('Neighbours must be numeric and >= 0');
      
    $this->_dataSet    = $dataSet;
    $this->_neighbours = $neighbours;
    
    if ($similarity) {
      $this->_similarity = $similarity;
    }
    else {
      require_once(dirname(__FILE__) . '/../Similarity/PearsonCorrelationSimilarity.class.php');
      
      $this->_similarity = new PearsonCorrelationSimilarity($this->_dataSet);
    }
  }

  public function userNeighbourhood($userId) {
    $neighbours = $this->_dataSet->userNeighbourhood($userId, $this->_neighbours);
    
    if (empty($neighbours))
      throw new InvalidArgumentException('Invalid user ID');
      
    return $neighbours;
  }
  
  public function getSimilarity() { return $this->_similarity; }
}