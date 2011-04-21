<?php

require_once(dirname(__FILE__) . '/../Interfaces/UserNeighbourhood.interface.php');

class UserNeighbourhoodKNN implements UserNeighbourhood {
  protected $_dataSet;
  protected $_neighbours;
  protected $_similarity;
  
  public static $DEFAULT_NEIGHBOUR_NUM = 30;

  public function __construct(DataSet $dataSet,
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
    $users  = $this->_dataSet->getUserIds();
    $scores = array();

    foreach ($users as $user) {
      if ($user == $userId) continue;
      
      $similarity = $this->_similarity->userSimilarity($userId, $user);
    
      if ($similarity > 0)
        $scores[$user] = $similarity; 
    }
    
    arsort($scores, SORT_NUMERIC); // reverse sort
    
    // return top n (key = userId, value = similarity value)
    return array_slice($scores, 0, $this->_neighbours, true);
  }
  
  public function getSimilarity() { return $this->_similarity; }
}