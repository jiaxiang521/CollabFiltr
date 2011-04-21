<?php 

/**
 * The UserNeighbourhood interface generates a neighbourhood of similar users
 * for any given user, based on a provided Similarity metric
 */
interface UserNeighbourhood {
  /**
   * Gets neighbourhood for a given user, where keys are user IDs and values similarity scores
   * @param int $userId
   * @return array 
   */
  public function userNeighbourhood($userId);
  
  /**
   * Gets similarity metric
   * @return Similarity similarity instance used
   */
  public function getSimilarity();
} 