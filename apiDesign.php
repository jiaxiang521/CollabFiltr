<?php

// Interfaces: DataSet, Similarity, Neighbourhood, Recommender

require_once('DataSet/FileDataSet.class.php');

$data = new FileDataSet('/Volumes/Data/Work/Work/Uni/Dissertation/100k.data');

echo 'Users Loaded: ' . $data->getNumUsers() . "\n";
echo 'Items Loaded: ' . $data->getNumItems() . "\n";

$userIds = $data->getUserIds();
sort($userIds);

echo 'Users IDs: ' . implode(',', $userIds) . "\n";

#$similarity = new PearsonCorrelationSimilarity();
#
#$users = new UserNeighbourhoodNN($data);
#$users->setSimilarity($similarity); // dependency injection
#$users->setNeighbours(30);
#
#$recommender = new UserBasedRecommender($data);
#$recommender->setNeighbourhood($users);
#
#$recommendations = $recommender->recommend(USER ID, 10);

