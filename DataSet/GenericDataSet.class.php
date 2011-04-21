<?php

require_once(dirname(__FILE__) . '/../Interfaces/DataSet.interface.php');
require_once(dirname(__FILE__) . '/../Model/User.class.php');
require_once(dirname(__FILE__) . '/../Model/Item.class.php');

class GenericDataSet implements DataSet {
  // $_users['userId']['itemId'] = rating (integer)
  // $_items['itemId']['userId'] = rating (integer)
  private $_users;
  private $_items;

  private $_userNum;
  private $_itemNum;

  private $_ratingMin;
  private $_ratingMax;

  // $rating_range = array(start_value, end_value) e.g array(1,5)
  public function __construct($users, $items, $rating_range) {
    if (!is_array($users) || !is_array($items))
      throw new InvalidArgumentException();

    if (!is_array($rating_range) || count($rating_range) != 2
        || !is_numeric($rating_range[0]) || !is_numeric($rating_range[1])
        || $rating_range[0] > $rating_range[1])
      throw new InvalidArgumentException();

    $this->_users = &$users;
    $this->_items = &$items;

    $this->_userNum = count($users);
    $this->_itemNum = count($items);

    $this->_ratingMin = $rating_range[0];
    $this->_ratingMax = $rating_range[1];
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
      if (!($item instanceof Item))
        throw new InvalidArgumentException('Not instance of Item');

      $item = $item->getId();
    }

    if (!isset($this->_items[$item]))
      throw new InvalidArgumentException('Invalid item ID');

    return $this->_items[$item];
  }

  public function getRatingMin() { return $this->_ratingMin; }
  public function getRatingMax() { return $this->_ratingMax; }
}

class GenericDataSetBuilder {
  // $_users['userId']['itemId'] = rating (integer)
  // $_items['itemId']['userId'] = rating (integer)
  private $_users;
  private $_items;

  private $_range;
  private $_built;

  public function __construct($range) {
    $this->_users = array();
    $this->_items = array();

    $this->_range = $range;
    $this->_built = false;
  }

  public function build() {
    if ($this->_built) return;

    $dataSet = new GenericDataSet($this->_users, $this->_items, $this->_range);

    unset($this->_users);
    unset($this->_items);

    $this->_built = true;
    return $dataSet;
  }

  public function getParams() {
    return array($this->_users, $this->_items, $this->_range);
  }

  public function loadLine($userId, $itemId, $rating) {
   if ($this->_built) return;

    // user array
    if ($userId != null) {
      if (isset($this->_users[$userId]))
        $this->_users[$userId][$itemId] = $rating;
      elseif ($itemId == null)
        $this->_users[$userId] = array();
      else
        $this->_users[$userId] = array($itemId => $rating);
    }

    // item array
    if ($itemId != null) {
      if (isset($this->_items[$itemId]))
        $this->_items[$itemId][$userId] = $rating;
      elseif ($userId == null)
        $this->_items[$itemId] = array();
      else
        $this->_items[$itemId] = array($userId => $rating);
    }
  }
}