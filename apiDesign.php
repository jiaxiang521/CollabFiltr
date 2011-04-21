<?php

// Interfaces: DataSet, Similarity, Neighbourhood, Recommender

require_once(dirname(__FILE__) . '/DataSet/FileDataSet.class.php');
require_once(dirname(__FILE__) . '/Similarity/PearsonCorrelationSimilarity.class.php');
require_once(dirname(__FILE__) . '/Neighbourhood/UserNeighbourhoodKNN.class.php');
require_once(dirname(__FILE__) . '/Neighbourhood/UserNeighbourhoodWeighted.class.php');
require_once(dirname(__FILE__) . '/Recommenders/UserBasedRecommender.class.php');
require_once(dirname(__FILE__) . '/Reputation/SimpleReputation.class.php');

$dataSet = new FileDataSet('/Volumes/Data/Work/Work/Uni/Dissertation/100k.data');

echo 'Users Loaded: ' . $dataSet->getNumUsers() . "\n";
echo 'Items Loaded: ' . $dataSet->getNumItems() . "\n";

$similarity = new PearsonCorrelationSimilarity($dataSet);
//$similarity = new EuclideanDistanceSimilarity();
//$similarity = new AdjustedCosineSimilarity();

//$reputation = new SimpleReputation($dataSet);

$userKNN     = new UserNeighbourhoodKNN($dataSet, UserNeighbourhoodKNN::$DEFAULT_NEIGHBOUR_NUM, $similarity);
$recommender = new UserBasedRecommender($dataSet, $userKNN);
//$recommender = new ItemBasedRecommender($dataSet, $userKNN);

$recommendations = $recommender->recommend(42, 100);

$output = array();
foreach ($recommendations as $recommendation => $score)
  $output[] = "$recommendation ($score)";
  
echo "Recommendations: " . implode(', ', $output) . "\n";

