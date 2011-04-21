<?php

require_once(dirname(__FILE__) . '/../Model/GenericRating.class.php');

class User {
  private $_id;
  private $_ratings; // Item ID -> Rating
  
  public function __construct($id, $data) {
    if (!is_array($data))
      throw new InvalidArgumentException();
  
    $this->_id      = $id;
    $this->_ratings = $data;
  }
  
  public function getId() { return $this->_id; }

  public function getRatings() {
    $ratings = array();
    
    foreach ($this->_ratings as $item => $rating)
      $ratings[] = new GenericRating($this->_id, $item, $rating);
    
    return $ratings;
  }
  
  public function getRating($itemId) {
    if (!isset($_ratings[$itemId]))
      return null;

    return new GenericRating($this->_id, $itemId, $_ratings[$itemId]);
  }
  
  public function getAverageRating($dataSet) {
    $ratingsSum = array_sum($this->_ratings);
  
    if (!$ratingsSum) {
      return ($dataSet->getRatingMin()
              + $dataSet->getRatingMax()) / 2;
    }
    
    return $ratingsSum / count($this->_ratings);
  }
}