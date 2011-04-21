<?php

require_once(dirname(__FILE__) . '/UserNeighbourhoodKNN.class.php');

class UserNeighbourhoodKNNCache extends UserNeighbourhoodKNN {
  protected $_cache;

  public function __construct($file,
                              DataSet $dataSet,
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
    
    $this->_cache = array();
    
    $fh = fopen($file, 'rb');
    if (!$fh) throw new InvalidArgumentException('Could not open file');

    while (!feof($fh) && $line = fgets($fh)) {
      list($userId, $data) = explode(':', $line);
      
      $neighbours = array();
      
      $i = 0;
      foreach (explode(',', $data) as $pair) {
        if (++$i > 30) break;
        
        list($neighbour, $similarity) = explode(' ', $pair);
        $neighbours[$neighbour] = $similarity;
      }
      
      $this->_cache[$userId] = $neighbours;
    }
    
    fclose($fh);
  }

  public function userNeighbourhood($userId) {
    if (!isset($this->_cache[$userId]))
      throw new InvalidArgumentException('Invalid user ID');
      
    return $this->_cache[$userId];
  }
  
  public function getSimilarity() { return $this->_similarity; }
}