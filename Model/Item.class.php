<?php

require_once(dirname(__FILE__) . '/../Model/GenericRating.class.php');

class Item {
  private $_id;
  private $_ratings;
  
  public function __construct($id, $data) {
    if (!is_array($data))
      throw new InvalidArgumentException();
  
    $this->_id      = $id;
    $this->_ratings = $data;
  }
  
  public function getId() { return $this->_id; }

  public function getRatings() {
    $ratings = array();
    
    foreach ($this->_ratings as $user => $rating)
      $ratings[] = new GenericRating($user, $this->_id, $rating);
    
    return $ratings;
  }
}