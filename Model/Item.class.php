<?php

class Item {
  private $_id;
  private $_ratings;
  
  public function __construct($data) {
    if (!is_array($data))
      throw new InvalidArgumentException();
  
    $this->_ratings = $data;
  }
  
  public function getId() { return $this->_id; }

  public function getRatings() {
    $ratings = array();
    
    foreach ($this->_ratings as $user => $rating)
      $ratings[] = new Rating($user, $this->_id, $rating);
    
    return $ratings;
  }
}