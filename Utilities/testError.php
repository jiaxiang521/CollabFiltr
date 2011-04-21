<?php

ini_set('memory_limit', '1024M');

require_once(dirname(__FILE__) . '/../DataSet/MovieLensDataSet.class.php');
require_once(dirname(__FILE__) . '/../DataSet/JesterDataSet.class.php');
require_once(dirname(__FILE__) . '/../Neighbourhood/UserNeighbourhoodKNNCache.class.php');
require_once(dirname(__FILE__) . '/../Neighbourhood/UserNeighbourhoodWeightedKNN.class.php');
require_once(dirname(__FILE__) . '/../Recommenders/UserBasedRecommender.class.php');

require_once(dirname(__FILE__) . '/../Similarity/PearsonCorrelationSimilarity.class.php');
require_once(dirname(__FILE__) . '/../Similarity/EuclideanDistanceSimilarity.class.php');
require_once(dirname(__FILE__) . '/../Similarity/RandomSimilarity.class.php');

function getNeighbourIds($file) {
  $fh = fopen($file, 'rb');
  if (!$fh) throw new InvalidArgumentException('Could not open file');

  $neighbours = array();

  while (!feof($fh) && $line = fgets($fh)) {
    list($userId) = explode(':', $line);
    $neighbours[] = $userId;
  }
  
  fclose($fh);
  return $neighbours;
}

$neighbourIds = getNeighbourIds('/Volumes/Data/Work/Work/Uni/Dissertation/php/Data/NeighbourCache/Jester/pearson.txt');

$time_start = microtime(true);


$dataSet = new JesterDataSet(dirname(__FILE__) . '/../Data/Jester/jester_ratings.dat',
                             250000,
                             $neighbourIds);
unset($neighbourIds);
//$dataSet = new MovieLensDataSet('/Volumes/Data/Work/Work/Uni/Dissertation/php/Data/MovieLens/large', 100000);

echo 'Users Loaded: ' . $dataSet->getNumUsers() . "\n";
echo 'Items Loaded: ' . $dataSet->getNumItems() . "\n";

$ratings = 0;
foreach($dataSet->getUserIds() as $userId)
  $ratings += count($dataSet->getUserRatingsArray($userId));
  
echo "Ratings Loaded: $ratings\n";

$sample = $dataSet->getSample();
echo "Sample Size: " . count($sample) . "\n";
unset($sample);

/*
echo "Getting neighbourhood cache...\n";
$userKNN = new UserNeighbourhoodKNNCache(dirname(__FILE__) . '/../Data/NeighbourCache/Jester/pearson.txt',
                                         $dataSet,
                                         UserNeighbourhoodKNN::$DEFAULT_NEIGHBOUR_NUM,
                                         new PearsonCorrelationSimilarity($dataSet));
echo "Got neighbourhood cache\n";
*/
/*
echo "Getting neighbourhood cache...\n";
$userKNN = new UserNeighbourhoodKNNCache(dirname(__FILE__) . '/../Data/NeighbourCache/Jester/random.txt',
                                         $dataSet,
                                         UserNeighbourhoodKNN::$DEFAULT_NEIGHBOUR_NUM,
                                         new RandomSimilarity($dataSet));
echo "Got neighbourhood cache\n";
*/
/*
echo "Getting neighbourhood cache...\n";
$userKNN = new UserNeighbourhoodKNNCache(dirname(__FILE__) . '/../Data/NeighbourCache/random.txt',
                                         $dataSet,
                                         UserNeighbourhoodKNN::$DEFAULT_NEIGHBOUR_NUM,
                                         new RandomSimilarity());
echo "Got neighbourhood cache\n";
*/
     
//$recommender = new UserBasedRecommender($dataSet, $userKNN);

$sample = $dataSet->getSample();
$sum1 = $sum2 = 0;

$i = 0;
$count = count($sample);

foreach ($sample as $rating) {
  $i++;

  $estimate = $recommender->estimateRating($rating->getUserId(), $rating->getItemId());
  //$estimate = 10.0;
  
  $sum1 += pow(($estimate - $rating->getRating()), 2);
  $sum2 += abs($estimate - $rating->getRating());
  
  if ($i % 10000 == 0)
    echo "Done $i of $count\n";
}

$rmse = sqrt($sum1 / $count);
$mae  = $sum2 / $count;

echo "RMSE: $rmse\n";
echo "MAE: $mae\n";

$time_end = microtime(true);
$time = $time_end - $time_start;

echo "Finished in $time seconds\n";