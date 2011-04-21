<?php 

/**
 * The DataSetUserNeighbourhood extends the DataSet interface by providing methods
 * for accessing user neighbourhood caches
 */
interface DataSetUserNeighbourhood {
  /**
   * Gets $num neighbours for the given user
   * @return array
   */
  public function userNeighbourhood($user, $num);
}