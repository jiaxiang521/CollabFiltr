<?php

require_once(dirname(__FILE__) . '/../Interfaces/UserNeighbourhood.interface.php');
require_once(dirname(__FILE__) . '/UserNeighbourhoodKNN.class.php');

class UserNeighbourhoodWeightedKNN extends UserNeighbourhoodKNN {
  protected $_reputation;

  public function __construct(DataSet $dataSet,
                              $neighbours,
                              UserSimilarity $similarity = null,
                              UserReputation $reputation = null) {
    parent::__construct($dataSet, $neighbours, $similarity);
    
    $this->_reputation = $reputation;                          
  }
                              
  public function userNeighbourhood($userId) {
    $users  = $this->_dataSet->getUserIds();
    $scores = array();

    foreach ($users as $user) {
      if ($user == $userId) continue;
      
      $similarity = $this->_similarity->userSimilarity($userId, $user);
    
      if ($similarity <= 0)
        continue;
      
      $scores[$user] = $similarity; 
      
      // apply reputational weighting
      if ($this->_reputation)
        $scores[$user] += $this->_reputation->userBias($userId, $user);
    }
    
    arsort($scores, SORT_NUMERIC); // reverse sort
    
    // return top n (key = userId, value = similarity value)
    return array_slice($scores, 0, $this->_neighbours, true);
  }
}