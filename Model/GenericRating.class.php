<?php

require_once(dirname(__FILE__) . '/../interfaces/Rating.interface.php');

class GenericRating implements Rating {
  private $_userId;
  private $_itemId;
  private $_rating;

  public function __construct($userId, $itemId, $rating) {
    $this->_userId = $userId;
    $this->_itemId = $itemId;
    $this->_rating = $rating;
  }

  public function getUserId() { return $this->_userId; }
  public function getItemId() { return $this->_itemId; }
  public function getRating() { return $this->_rating; }
}