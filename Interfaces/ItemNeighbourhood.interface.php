<?php 

/**
 * The ItemNeighbourhood interface generates a neighbourhood of similar items
 * for any given item, based on a provided Similarity metric
 */
interface ItemNeighbourhood {
  /**
   * Gets neighbourhood for a given item, where keys are item IDs and values similarity scores
   * @param int $itemId
   * @return array 
   */
  public function itemNeighbourhood($itemId);
  
  /**
   * Gets similarity metric
   * @return Similarity similarity instance used
   */
  public function getSimilarity();
} 