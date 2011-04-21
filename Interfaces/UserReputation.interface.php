<?php 

interface UserReputation {
  public function userReputation($userId);
  
  public function userBias($userId1, $userId2);
} 