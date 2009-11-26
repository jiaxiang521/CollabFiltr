<?php 

interface DataSet {
  public function getUserIds();
  public function getItemIds();
  
  public function getNumUsers();
  public function getNumItems();
  
  public function getUser($userId);
  public function getItem($itemId);

  public function getUserRatingsArray($user);
  public function getItemRatingsArray($item);
}