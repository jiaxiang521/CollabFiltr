<?php

require_once(dirname(__FILE__) . '/DemoDataSet.class.php');
require_once(dirname(__FILE__) . '/../Similarity/PearsonCorrelationSimilarity.class.php');
require_once(dirname(__FILE__) . '/../Neighbourhood/UserNeighbourhoodKNN.class.php');
require_once(dirname(__FILE__) . '/../Recommenders/UserBasedRecommender.class.php');

$dataSet     = new DemoDataSet();
$similarity  = new PearsonCorrelationSimilarity($dataSet);
$userKNN     = new UserNeighbourhoodKNN($dataSet, UserNeighbourhoodKNN::$DEFAULT_NEIGHBOUR_NUM, $similarity);
$recommender = new UserBasedRecommender($dataSet, $userKNN);

$recommendations = $recommender->recommend('Lisa Rose', 3);
die(print_r($recommendations));