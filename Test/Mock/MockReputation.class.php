<?php

require_once(dirname(__FILE__) . '/../../Interfaces/UserReputation.interface.php');

class MockReputation implements UserReputation {
  public function userReputation($userId) {
    return $this->userBias($userId1, $userId1);
  }

  public function userBias($userId1, $userId2) {
    if (!isset($this->_reputation[$userId1]) || !isset($this->_reputation[$userId1][$userId2]))
      throw new InvalidArgumentException('Invalid users');
      
     // TODO
  }
}