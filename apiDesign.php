<?php

// Interfaces: DataSet, Similarity, Neighbourhood, Recommender

require_once(dirname(__FILE__) . '/DataSet/FileDataSet.class.php');
require_once(dirname(__FILE__) . '/Similarity/PearsonCorrelationSimilarity.class.php');
require_once(dirname(__FILE__) . '/Neighbourhood/UserNeighbourhoodNN.class.php');

$dataSet = new FileDataSet('/Volumes/Data/Work/Work/Uni/Dissertation/100k.data');

echo 'Users Loaded: ' . $dataSet->getNumUsers() . "\n";
echo 'Items Loaded: ' . $dataSet->getNumItems() . "\n";

$userIds = $dataSet->getUserIds();
sort($userIds);

echo 'Users IDs: ' . implode(',', $userIds) . "\n";

$similarity = new PearsonCorrelationSimilarity($dataSet);
#$similarity = new EuclideanDistanceSimilarity();

$userNN = new UserNeighbourhoodNN($dataSet, 30, $similarity);
$recommender = new UserBasedRecommender($dataSet, $userNN);

$recommendations = $recommender->recommend(USER ID, 5);

print_r($recommendations);

