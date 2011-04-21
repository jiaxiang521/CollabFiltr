<?php

ini_set('memory_limit', '1024M');

require_once(dirname(__FILE__) . '/../DataSet/MovieLensDataSet.class.php');
require_once(dirname(__FILE__) . '/../DataSet/JesterDataSet.class.php');
require_once(dirname(__FILE__) . '/../Neighbourhood/UserNeighbourhoodKNN.class.php');

require_once(dirname(__FILE__) . '/../Similarity/PearsonCorrelationSimilarity.class.php');
require_once(dirname(__FILE__) . '/../Similarity/EuclideanDistanceSimilarity.class.php');
require_once(dirname(__FILE__) . '/../Similarity/RandomSimilarity.class.php');

//$dataSet = new MovieLensDataSet('/Volumes/Data/Work/Work/Uni/Dissertation');
$dataSet = new JesterDataSet('/Volumes/Data/Work/Work/Uni/Dissertation/php/Data/Jester/jester_ratings.dat');

echo 'Users Loaded: ' . $dataSet->getNumUsers() . "\n";
echo 'Items Loaded: ' . $dataSet->getNumItems() . "\n";

$ratings = 0;
foreach($dataSet->getUserIds() as $userId)
  $ratings += count($dataSet->getUserRatingsArray($userId));
  
echo "Ratings Loaded: $ratings\n";

$fh = fopen('neighboursPearson.txt', 'w');
if (!$fh) die("Could not open file\n");

$userKNN = new UserNeighbourhoodKNN($dataSet, 60);
$count   = count($dataSet->getUserIds());

foreach ($dataSet->getUserIds() as $userId) {
  $neighbours = $userKNN->userNeighbourhood($userId);
  
  $line = '';
  foreach ($neighbours as $neighbour => $similarity)
    $line .= "$neighbour $similarity,";
  $line = rtrim($line, ',');
  
  fwrite($fh, "$userId:$line\n");
}

fclose($fh);
