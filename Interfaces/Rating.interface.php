<?php 

/**
 * The Rating interface encapsulates user ratings of items
 */
interface Rating {
  /**
   * Gets the user ID for the rating
   * @return int
   */
  public function getUserId();
  
  /**
   * Gets the item ID for the rating
   * @return int
   */
  public function getItemId();
  
  /**
   * Gets the numerical rating
   * @return double
   */
  public function getRating();
}