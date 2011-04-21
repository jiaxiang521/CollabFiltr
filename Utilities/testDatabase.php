<?php

define('DB_SOCK', '/Applications/MAMP/tmp/mysql/mysql.sock');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'collabfiltr');

ini_set('memory_limit', '1024M');

require_once(dirname(__FILE__) . '/../DataSet/MovieLensDataSet.class.php');
require_once(dirname(__FILE__) . '/../DataSet/MovieLensDatasetDatabase.class.php');

require_once(dirname(__FILE__) . '/../Neighbourhood/UserNeighbourhoodKNN.class.php');
require_once(dirname(__FILE__) . '/../Neighbourhood/UserNeighbourhoodKNNCache.class.php');
require_once(dirname(__FILE__) . '/../Neighbourhood/UserNeighbourhoodKNNCacheDatabase.class.php');
require_once(dirname(__FILE__) . '/../Recommenders/UserBasedRecommender.class.php');

echo "Loading Dataset...\n";
$start = microtime(true);
$dataSet = new MovieLensDataSetDatabase(DB_USER, DB_PASS, DB_SOCK, DB_NAME);
printTime($start);

echo "Getting stats...\n";
$start = microtime(true);
printStats($dataSet);
printTime($start);

echo "Getting neighbourhood for a sample user...\n";
$start = microtime(true);

$userKNN = new UserNeighbourhoodKNN($dataSet, 30);
$neighbours = $userKNN->userNeighbourhood(560);
ksort($neighbours);
arsort($neighbours);
echo 'Neighbours: ' . implode(',', array_keys($neighbours)) . "\n";

printTime($start);
echo "\n";

echo "Getting recommendations...\n";
$start = microtime(true);

$recommender = new UserBasedRecommender($dataSet, $userKNN);
$recommender->recommend(560, 5);

printTime($start);
echo "\n";

echo "Getting neighbourhood for a sample user (cache)...\n";
$start = microtime(true);

$userKNN = new UserNeighbourhoodKNNCacheDatabase($dataSet, 30);
$neighbours = $userKNN->userNeighbourhood(560);
ksort($neighbours);
arsort($neighbours);
echo 'Neighbours: ' . implode(',', array_keys($neighbours)) . "\n";

printTime($start);
echo "\n";

echo "Getting recommendations...\n";
$recommender = new UserBasedRecommender($dataSet, $userKNN);
$recommender->recommend(560, 5);

printTime($start);
echo "\n";

$start = microtime(true);
echo "Loading Dataset...\n";
$dataSet = new MovieLensDataSet('/Volumes/Data/Work/Work/Uni/Dissertation/php/Data/MovieLens/large');
printTime($start);

echo "Getting stats...\n";
$start = microtime(true);
printStats($dataSet);
printTime($start);

echo "Getting neighbourhood for a sample user...\n";
$start = microtime(true);

$userKNN = new UserNeighbourhoodKNN($dataSet, 30);
$neighbours = $userKNN->userNeighbourhood(560);
ksort($neighbours);
arsort($neighbours);
echo 'Neighbours: ' . implode(',', array_keys($neighbours)) . "\n";

printTime($start);
echo "\n";

echo "Getting recommendations...\n";
$start = microtime(true);

$recommender = new UserBasedRecommender($dataSet, $userKNN);
$recommender->recommend(560, 5);

printTime($start);
echo "\n";

echo "Getting neighbourhood for a sample user (cache)...\n";
$start = microtime(true);

$userKNN = new UserNeighbourhoodKNNCache(dirname(__FILE__) . '/../Data/NeighbourCache/pearson.txt', $dataSet, 30);
$neighbours = $userKNN->userNeighbourhood(560);
ksort($neighbours);
arsort($neighbours);
echo 'Neighbours: ' . implode(',', array_keys($neighbours)) . "\n";

printTime($start);
echo "\n";

echo "Getting recommendations...\n";
$start = microtime(true);

$recommender = new UserBasedRecommender($dataSet, $userKNN);
$recommender->recommend(560, 5);

printTime($start);
echo "\n";

echo "Getting neighbourhood for another sample user (cache)...\n";
$start = microtime(true);

$neighbours = $userKNN->userNeighbourhood(565);
ksort($neighbours);
arsort($neighbours);
echo 'Neighbours: ' . implode(',', array_keys($neighbours)) . "\n";

printTime($start);
echo "\n";

echo "Getting recommendations...\n";
$start = microtime(true);

$recommender = new UserBasedRecommender($dataSet, $userKNN);
$recommender->recommend(560, 5);

printTime($start);
echo "\n";

function printStats($dataSet) {
  echo 'Users Loaded: ' . $dataSet->getNumUsers() . "\n";
  echo 'Items Loaded: ' . $dataSet->getNumItems() . "\n";
  
  $ratings = 0;
  foreach($dataSet->getUserIds() as $userId)
    $ratings += count($dataSet->getUserRatingsArray($userId));
    
  echo "Ratings Loaded: $ratings\n";
}

function printTime($start) {
  $end  = microtime(true);
  $time = $end - $start;

  echo "Finished in $time seconds\n";
}