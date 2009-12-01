<?php 

interface Recommender {
  public function recommend($userId, $num);
  public function estimateRating($userId, $itemId);
}