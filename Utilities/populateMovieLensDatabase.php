<?php

define('DB_SOCK', '/Applications/MAMP/tmp/mysql/mysql.sock');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'collabfiltr');

ini_set('memory_limit', '1024M');
require_once(dirname(__FILE__) . '/../DataSet/MovieLensDataSet.class.php');

$dbh     = new PDO('mysql:unix_socket=' . DB_SOCK . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
$dataSet = new MovieLensDataSet('/Volumes/Data/Work/Work/Uni/Dissertation/php/Data/MovieLens/large');

foreach($dataSet->getUserIds() as $userId) {
  addUser($userId);

  foreach ($dataSet->getUserRatingsArray($userId) as $itemId => $rating)
    addRating($userId, $itemId, $rating);
}

foreach($dataSet->getItemIds() as $itemId)
  addItem($itemId);
    
function addUser($user) {
  global $dbh;

  try {
    $sth = $dbh->prepare('INSERT INTO ml_users(user) VALUES (:user)');
    $sth->bindValue(':user', $user);

    $sth->execute();
  } catch (PDOException $e){
     die('ERROR: ' . $e->getMessage () . "\n");
  }
}

function addItem($item) {
  global $dbh;

  try {
    $sth = $dbh->prepare('INSERT INTO ml_items(item) VALUES (:item)');
    $sth->bindValue(':item', $item);

    $sth->execute();
  } catch (PDOException $e){
     die('ERROR: ' . $e->getMessage () . "\n");
  }
}
    
function addRating($user, $item, $rating) {
  global $dbh;

  try {
    $sth = $dbh->prepare('INSERT INTO ml_ratings(user, item, rating) VALUES (:user, :item, :rating)');
    $sth->bindValue(':user',  $user);
    $sth->bindValue(':item',  $item);
    $sth->bindValue(':rating', $rating);

    $sth->execute();
  } catch (PDOException $e){
     die('ERROR: ' . $e->getMessage () . "\n");
  }
}
