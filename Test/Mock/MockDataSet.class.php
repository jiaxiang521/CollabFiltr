<?php

class MockDataSet implements DataSet {
  private $_users;

  public function __construct() {
    $this->_users = array('Lisa Rose' => array('Lady in the Water'  => 2.5,
                                               'Snakes on a Plane'  => 3.5,
                                               'Just My Luck'       => 3.0,
                                               'Superman Returns'   => 3.5,
                                               'You, Me and Dupree' => 2.5,
                                               'The Night Listener' => 3.0),
                                               
                          'Gene Seymour' => array('Lady in the Water'  => 3.0,
                                                  'Snakes on a Plane'  => 3.5,
                                                  'Just My Luck'       => 1.5,
                                                  'Superman Returns'   => 5.0,
                                                  'The Night Listener' => 3.0,
                                                  'You, Me and Dupree' => 3.5),
                                                  
                          'Michael Phillips' => array('Lady in the Water'  => 2.5,
                                                      'Snakes on a Plane'  => 3.0,
                                                      'Superman Returns'   => 3.5,
                                                      'The Night Listener' => 4.0),
                          
                          'Claudia Puig' => array('Snakes on a Plane'  => 3.5,
                                                  'Just My Luck'       => 3.0,
                                                  'The Night Listener' => 4.5,
                                                  'Superman Returns'   => 4.0, 
                                                  'You, Me and Dupree' => 2.5),
                          
                          'Mick LaSalle' => array('Lady in the Water'  => 3.0,
                                                  'Snakes on a Plane'  => 4.0, 
                                                  'Just My Luck'       => 2.0,
                                                  'Superman Returns'   => 3.0,
                                                  'The Night Listener' => 3.0,
                                                  'You, Me and Dupree' => 2.0), 
                          
                          'Jack Matthews' => array('Lady in the Water'  => 3.0,
                                                   'Snakes on a Plane'  => 4.0,
                                                   'The Night Listener' => 3.0,
                                                   'Superman Returns'   => 5.0,
                                                   'You, Me and Dupree' => 3.5),
                          
                          'Toby' => array('Snakes on a Plane'  => 4.5,
                                          'You, Me and Dupree' => 1.0,
                                          'Superman Returns' => 4.0));
  }

  public function getUserIds()  { return array_keys($this->_users); }
  
  public function getUserRatingsArray($user) { return $this->_users[$user]; }
  
  public function isUser($user) { return isset($this->_users[$user]); }

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

  public function getItemIds()  { }
  public function getNumUsers() { }
  public function getNumItems() { }
  public function isItem($a)    { }
  public function getItemRatingsArray($a) { }
}