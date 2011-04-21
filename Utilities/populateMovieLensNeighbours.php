<?php

define('DB_SOCK', '/Applications/MAMP/tmp/mysql/mysql.sock');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'collabfiltr');

ini_set('memory_limit', '1024M');
require_once(dirname(__FILE__) . '/../DataSet/MovieLensDataSet.class.php');

$dbh = new PDO('mysql:unix_socket=' . DB_SOCK . ';dbname=' . DB_NAME, DB_USER, DB_PASS);

$fh = fopen(dirname(__FILE__) . '/../Data/neighboursPearson.txt', 'rb');
if (!$fh) throw new InvalidArgumentException('Could not open file');

while (!feof($fh) && $line = fgets($fh)) {
  list($userId, $data) = explode(':', $line);

  $neighbours = array();

  $i = 0;
  foreach (explode(',', $data) as $pair) {
    if (++$i > 60) break;

    list($neighbour, $similarity) = explode(' ', $pair);
    addNeighbour($userId, $neighbour, $similarity);
  }
}

function addNeighbour($user, $neighbour, $similarity) {
  global $dbh;

  try {
    $sth = $dbh->prepare('INSERT INTO ml_neighbours(user, neighbour, similarity)
                          VALUES (:user, :neighbour, :similarity)');
    $sth->bindValue(':user', $user);
    $sth->bindValue(':neighbour', $neighbour);
    $sth->bindValue(':similarity', $similarity);

    $sth->execute();
  } catch (Exception $e){
     die('ERROR: ' . $e->getMessage () . "\n");
  }
}