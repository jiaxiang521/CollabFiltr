<?php

require_once(dirname(__FILE__) . '/../DataSet/FileDataSet.class.php');
require_once(dirname(__FILE__) . '/../Interfaces/UserSimilarity.interface.php');
require_once(dirname(__FILE__) . '/../Similarity/PearsonCorrelationSimilarity.class.php');
require_once(dirname(__FILE__) . '/../Neighbourhood/UserNeighbourhoodKNN.class.php');

class TheSixtyOneDataSet extends FileDataSet {
  protected $_dataSet;

  public function __construct($file) {
    $fh = fopen($file, 'r');
    if (!$fh) throw new RuntimeException('Could not open file');
    
    $builder = new GenericDataSetBuilder(array(1,1));

    $i = 0;
    $itemId = null;
        
    while (!feof($fh) && $line = fgets($fh)) {
      // got item ID
      if (++$i % 2 == 1) {
        $itemId = trim($line);
        continue;
      }
      
      $users = explode(' ', $line);
      foreach ($users as $userId) {
        $userId = trim($userId);
        $builder->loadLine($userId, $itemId, 1);
      }
    }

    fclose($fh);
    $this->_dataSet = $builder->build();
  }
}

class TheSixtyOneSimilarity implements UserSimilarity {
  private $_dataSet;
  
  public function __construct(Dataset $dataset) {
    $this->_dataSet = $dataset;
  }
  
  public function userSimilarity($userId1, $userId2) {
    $ratings1 = $this->_dataSet->getUserRatingsArray($userId1);
    $ratings2 = $this->_dataSet->getUserRatingsArray($userId2);
    
    $mutuallyRated = array_intersect(array_keys($ratings1),
                                     array_keys($ratings2));
                                     
    $mutuallyRatedNum = count($mutuallyRated);
    if (!$mutuallyRatedNum) return 0;

    $totalRatings = count($ratings1) + count($ratings2);
    $difference   = ($mutuallyRatedNum / $totalRatings);
    
    return $mutuallyRatedNum * ($difference * 10);
  }
}

class UserNeighbourhoodThreshold extends UserNeighbourhoodKNN {
  public function userNeighbourhood($userId) {
    $users  = $this->_dataSet->getUserIds();
    $scores = array();
    
    foreach ($users as $user) {
      if ($user == $userId) continue;
      
      $similarity = $this->_similarity->userSimilarity($userId, $user);
    
      if ($similarity >= 5)
        $scores[$user] = $similarity; 
    }
    
    arsort($scores, SORT_NUMERIC); // reverse sort
    return $scores;
  }
}
  
$dataSet    = new TheSixtyOneDataSet('/Volumes/Data/Work/Programming/Perl/voting/voting_ring.txt');
$similarity = new TheSixtyOneSimilarity($dataSet);
$userKNN    = new UserNeighbourhoodThreshold($dataSet, UserNeighbourhoodKNN::$DEFAULT_NEIGHBOUR_NUM, $similarity);

echo 'Users Loaded: ' . $dataSet->getNumUsers() . "\n";
echo 'Items Loaded: ' . $dataSet->getNumItems() . "\n";

$ratings = 0;
foreach($dataSet->getUserIds() as $userId)
  $ratings += count($dataSet->getUserRatingsArray($userId));
  
echo "Ratings Loaded: $ratings\n\n";

$neighbours   = $userKNN->userNeighbourhood(114317);
$neighbours[] = 114317;
ksort($neighbours);

echo implode(' ', array_keys($neighbours)) . "\n";