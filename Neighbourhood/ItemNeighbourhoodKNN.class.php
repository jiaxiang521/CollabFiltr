<?php

require_once(dirname(__FILE__) . '/../Interfaces/ItemNeighbourhood.interface.php');

class ItemNeighbourhoodKNN implements ItemNeighbourhood {
  protected $_dataSet;
  protected $_neighbours;
  protected $_similarity;
  
  public static $DEFAULT_NEIGHBOUR_NUM = 30;

  public function __construct(DataSet $dataSet,
                              $neighbours,
                              ItemSimilarity $similarity = null) {
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

  public function itemNeighbourhood($itemId) {
    $items  = $this->_dataSet->getItemIds();
    $scores = array();

    foreach ($items as $item) {
      if ($item == $itemId) continue;
      
      $similarity = $this->_similarity->itemSimilarity($itemId, $item);
    
      if ($similarity > 0)
        $scores[$item] = $similarity; 
    }
    
    arsort($scores, SORT_NUMERIC); // reverse sort
    
    // return top n (key = userId, value = similarity value)
    return array_slice($scores, 0, $this->_neighbours, true);
  }
  
  public function getSimilarity() { return $this->_similarity; }
}