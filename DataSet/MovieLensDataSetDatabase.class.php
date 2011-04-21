<?php

ini_set('memory_limit', '1024M');

require_once(dirname(__FILE__) . '/../Interfaces/DataSet.interface.php');
require_once(dirname(__FILE__) . '/../Interfaces/DataSetUserNeighbourhood.interface.php');

class MovieLensDataSetDatabase implements DataSet, DataSetUserNeighbourhood {
  private $_dbh;
  private $_db;

  public function __construct($user, $pass, $sock, $name) {
    $this->_dbh  = new PDO("mysql:unix_socket=$sock;dbname=$name", $user, $pass);
    $this->_db   = $name;
  }

  public function getUserIds() {
    $sth = $this->_dbh->query('SELECT user
                                 FROM ml_users');
    return $sth->fetchAll(PDO::FETCH_COLUMN);
  }

  public function getItemIds() {
    $sth = $this->_dbh->query('SELECT item
                                 FROM ml_items');
    return $sth->fetchAll(PDO::FETCH_COLUMN);
  }

  public function getNumUsers() {
    $sth = $this->_dbh->query('SELECT COUNT(*)
                                 FROM ml_users');
    return $sth->fetchColumn();
  }

  public function getNumItems() {
    $sth = $this->_dbh->query('SELECT COUNT(*)
                                 FROM ml_items');
    return $sth->fetchColumn();
  }

  public function isUser($userId) {
    $sth = $this->_dbh->prepare('SELECT user
                                   FROM ml_users
                                  WHERE user = :user');
    $sth->bindValue(':user', $userId);
    $sth->execute();

    return $sth->fetch() ? true : false;
  }

  public function isItem($itemId) {
    $sth = $this->_dbh->prepare('SELECT item
                                   FROM ml_items
                                  WHERE item = :item');
    $sth->bindValue(':item', $itemId);
    $sth->execute();

    return $sth->fetch() ? true : false;
  }

  public function getUser($userId) {
    if (!$this->isUser($userId))
      throw new InvalidArgumentException();

    $sth = $this->_dbh->prepare('SELECT item, rating
                                   FROM ml_ratings
                                  WHERE user = :user');
    $sth->bindValue(':user', $userId);
    $sth->execute();

    $user = array();

    while ($rs = $sth->fetch(PDO::FETCH_ASSOC))
      $user[$rs['item']] = $rs['rating'];

    return new User($userId, $user);
  }

  public function getItem($itemId) {
    if (!$this->isItem($userId))
      throw new InvalidArgumentException();

    $sth = $this->_dbh->prepare('SELECT user, rating
                                   FROM ml_ratings
                                  WHERE item = :item');
    $sth->bindValue(':item', $itemId);
    $sth->execute();

    $item = array();

    while ($rs = $sth->fetch(PDO::FETCH_ASSOC))
      $item[$rs['user']] = $rs['rating'];

    return new Item($itemId, $item);
  }

  public function getUserRatingsArray($user) {
    $sth = $this->_dbh->prepare('SELECT item, rating
                                   FROM ml_ratings
                                  WHERE user = :user');
    $sth->bindValue(':user', $user);
    $sth->execute();

    $result = array();

    while ($rs = $sth->fetch(PDO::FETCH_ASSOC))
      $result[$rs['item']] = $rs['rating'];

    return $result;
  }

  public function getItemRatingsArray($item) {
    $sth = $this->_dbh->prepare('SELECT item, rating
                                   FROM ml_ratings
                                  WHERE item = :item');
    $sth->bindValue(':item', $item);
    $sth->execute();

    $result = array();

    while ($rs = $sth->fetch(PDO::FETCH_ASSOC))
      $result[$rs['user']] = $rs['rating'];

    return $result;
  }

  public function userNeighbourhood($userId, $num) {
    $sth = $this->_dbh->prepare('SELECT neighbour, similarity
                                   FROM ml_neighbours
                                  WHERE user = :user
                                  ORDER BY similarity DESC
                                  LIMIT 0,' . (int)$num);
    $sth->bindValue(':user', $userId);
    $sth->execute();

    $result = array();

    while ($rs = $sth->fetch(PDO::FETCH_ASSOC))
      $result[$rs['neighbour']] = $rs['similarity'];
      
    return $result;
  }

  public function getRatingMin() { return 1; }
  public function getRatingMax() { return 5; }
}