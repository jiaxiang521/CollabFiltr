<?php

// Interfaces: DataSet, Similarity, Neighbourhood, Recommender

require_once(dirname(__FILE__) . '/DataSet/FileDataSet.class.php');
require_once(dirname(__FILE__) . '/Similarity/PearsonCorrelationSimilarity.class.php');

$dataSet = new FileDataSet('/Volumes/Data/Work/Work/Uni/Dissertation/100k.data');

echo 'Users Loaded: ' . $dataSet->getNumUsers() . "\n";
echo 'Items Loaded: ' . $dataSet->getNumItems() . "\n";

$userIds = $dataSet->getUserIds();
sort($userIds);

echo 'Users IDs: ' . implode(',', $userIds) . "\n";

$similarity = new PearsonCorrelationSimilarity($dataSet);
#$similarity = new EuclideanDistanceSimilarity();

#$users = new UserNeighbourhoodNN($data);
#$users->setSimilarity($similarity); // dependency injection
#$users->setNeighbours(30);
#
#$recommender = new UserBasedRecommender($data);
#$recommender->setNeighbourhood($users);
#
#$recommendations = $recommender->recommend(USER ID, 10);

