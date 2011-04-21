<?php 

/**
 * The DataSet interface provides an API to access a given data set
 * in a uniform fashion. It provides a minimalist API with methods
 * enabling access to the users, items, and rating data
 */
interface DataSet {
  /**
   * Gets all the user IDs in the data set
   * @return array
   */
  public function getUserIds();
  
  /**
   * Gets all the item IDs in the data set
   * @return array
   */
  public function getItemIds();
  
  /**
   * Gets number of users in the data set
   * @return int
   */
  public function getNumUsers();
  
  /**
   * Gets number of items in the data set
   * @return int
   */
  public function getNumItems();

  /**
   * Returns whether or not the given user ID is in the data set
   * @param int $userId
   * @return bool
   */
  public function isUser($userId);
  
  /**
   * Returns whether or not the given item ID is in the data set
   * @param int $itemId
   * @return bool
   */
  public function isItem($itemId);
  
  /**
   * Gets a User object for the given ID
   * @param int $userId
   * @return User
   */
  public function getUser($userId);
  
  /**
   * Gets an Item object for the given ID
   * @param int $itemId
   * @return Item
   */
  public function getItem($itemId);

  /**
   * Gets all the ratings for the given user, where keys are
   * item IDs and values are ratings
   * @param int|User $userId
   * @return array
   */
  public function getUserRatingsArray($user);
  
  /**
   * Gets all the ratings for the given item, where keys are
   * user IDs and values are ratings
   * @param int|Item $item
   * @return array
   */
  public function getItemRatingsArray($item);
  
  /**
   * Gets minimum rating number e.g 1
   * If int, only integer numbers allowed
   * @return int|double
   */
  public function getRatingMin();
  
  /**
   * Gets maximum rating number e.g 5
   * If int, only integer numbers allowed
   * @return int|double
   */
  public function getRatingMax();
}