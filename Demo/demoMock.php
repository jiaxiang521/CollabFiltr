<?php

require_once(dirname(__FILE__) . '/DemoDataSet.class.php');
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

$stdin = fopen('php://stdin', 'r');

echo "Loading demo data set...\n";
$dataSet = new DemoDataSet();
echo 'Users Loaded: ' . $dataSet->getNumUsers() . "\n";
echo 'Items Loaded: ' . $dataSet->getNumItems() . "\n";
pause();

echo "Loading 'Pearson Correlation' similarity metric...\n";
$similarity = new PearsonCorrelationSimilarity($dataSet);
pause();

echo "Loading neighbourhood generator with 3 neighbours\n";
$userKNN = new UserNeighbourhoodKNN($dataSet, 3, $similarity);
pause();

echo "Loading user recommender using the neighbourhood\n";
$recommender = new DemoRecommender($dataSet, $userKNN);
pause();

$user = getUser();
$num  = getNum();
$recommendations = $recommender->recommend($user, $num);

echo "Generated recommendations\n";
pause();

$output = array();
foreach ($recommendations as $recommendation => $score)
  $output[] = "$recommendation ($score)";
echo "Recommendations: " . implode(', ', $output) . "\n";
