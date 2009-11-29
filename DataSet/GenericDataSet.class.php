<?php

require_once(dirname(__FILE__) . '/../interfaces/DataSet.interface.php');
require_once(dirname(__FILE__) . '/../Model/User.class.php');
require_once(dirname(__FILE__) . '/../Model/Item.class.php');

final class GenericDataSet implements DataSet {
  // $_users['userId']['itemId'] = rating (integer)
  // $_items['itemId']['userId'] = rating (integer)
  private $_users;
  private $_items;
  
  private $_userNum;
  private $_itemNum;
  
  public function __construct($users, $items) {
    if (!is_array($users) || !is_array($items))
      throw new InvalidArgumentException();
  
    $this->_users = &$users;
    $this->_items = &$items;
    
    $this->_userNum = count($users);
    $this->_itemNum = count($items);
  }

  public function getUserIds() { return array_keys($this->_users); }
  public function getItemIds() { return array_keys($this->_items); }
  
  public function getNumUsers() { return $this->_userNum; }
  public function getNumItems() { return $this->_itemNum; }
  
  public function isUser($userId) { return isset($this->_users[$userId]); }
  public function isItem($itemId) { return isset($this->_items[$itemId]); }
  
  public function getUser($userId) {
    if (!isset($this->_users[$userId]))
      throw new InvalidArgumentException();

    return new User($userId, $this->_users[$userId]);
  }

  public function getItem($itemId) {
    if (!isset($this->_items[$itemId]))
      throw new InvalidArgumentException();

    return new Item($itemId, $this->_items[$itemId]);
  }

  public function getUserRatingsArray($user) {
    if (is_object($user)) {
      if (!($user instanceof User))
        throw new InvalidArgumentException('Not instance of User');
    
      $user = $user->getId();        
    }
  
    if (!isset($this->_users[$user]))
      throw new InvalidArgumentException('Invalid user ID');
    
    return $this->_users[$user];
  }

  public function getItemRatingsArray($item) {
    if (is_object($item)) {
      if (!($user instanceof Item))
        throw new InvalidArgumentException('Not instance of Item');
      
      $item = $item->getId();
    }
  
    if (!isset($this->_items[$item]))
      throw new InvalidArgumentException('Invalid item ID');
    
    return $this->_items[$item];
  }
}

class GenericDataSetBuilder {
  // $_users['userId']['itemId'] = rating (integer)
  // $_items['itemId']['userId'] = rating (integer)
  private $_users;
  private $_items;
  
  private $_built;
  
  public function __construct() {
    $this->_users = array();
    $this->_items = array();
    
    $this->_built = false;
  }
  
  public function build() {
    if ($this->_built) return;
  
    $dataSet = new GenericDataSet($this->_users, $this->_items);
    
    unset($this->_users);
    unset($this->_items);
    
    $this->_built = true;
    return $dataSet;
  }
  
  public function loadLine($userId, $itemId, $rating) {
   if ($this->_built) return;
  
    // user array
    if (isset($this->_users[$userId]))
      $this->_users[$userId][$itemId] = $rating;
    else
      $this->_users[$userId] = array($itemId => $rating);
      
    // item array
    if (isset($this->_items[$itemId]))
      $this->_items[$itemId][$userId] = $rating;
    else
      $this->_items[$itemId] = array($userId => $rating);
  }
}