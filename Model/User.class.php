<?php

class User {
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
    
    foreach ($this->_ratings as $item => $rating)
      $ratings[] = new Rating($this->_id, $item, $rating);
    
    return $ratings;
  }
}