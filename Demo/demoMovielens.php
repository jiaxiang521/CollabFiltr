<?php

require_once(dirname(__FILE__) . '/../DataSet/MovieLensDataSet.class.php');
require_once(dirname(__FILE__) . '/../Similarity/PearsonCorrelationSimilarity.class.php');
require_once(dirname(__FILE__) . '/../Neighbourhood/UserNeighbourhoodKNN.class.php');
require_once(dirname(__FILE__) . '/DemoRecommender.class.php');

function pause() {
  global $stdin;

  echo 'Continue? ';
  $line = fgets($stdin);
  
  if (trim($line) != 'y') return pause($stdin);
}

function getUser() {
  global $dataSet, $stdin;

  echo 'User to generate recommendations for: ';
  $user = trim(fgets($stdin));
  
  if (!$dataSet->isUser($user)) {
    echo "Invalid user\n";
    return getUser();
  }
  
  return $user;
}

function getNum() {
  global $dataSet, $stdin;

  echo 'Number of recommendations to generate: ';
  $num = trim(fgets($stdin));
  
  if (!is_numeric($num) || $num < 0) {
    echo "Invalid number\n";
    return getNum();
  }
  
  return $num;
}

$dataSet     = new MovieLensDataSet(dirname(__FILE__) . '/../Data/MovieLens/small');
$similarity  = new PearsonCorrelationSimilarity($dataSet);
$userKNN     = new UserNeighbourhoodKNN($dataSet, UserNeighbourhoodKNN::$DEFAULT_NEIGHBOUR_NUM, $similarity);
$recommender = new UserBasedRecommender($dataSet, $userKNN);

$user = getUser();
$num  = getNum();
$recommendations = $recommender->recommend($user, $num); // 583

die(print_r($recommendations));