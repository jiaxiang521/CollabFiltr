<?php

// only SimpleTest an be run directly via PHP CLI or the web
if (isset($argc) || (!isset($_SERVER['argc'])))
  $TEST_BED = 'SimpleTest';
else
  $TEST_BED = 'PHPUnit';
  
if (!isset($TEST_BED))
  die('Unknown test bed, TEST_BED must be SimpleTest or PHPUnit');

if ($TEST_BED == 'SimpleTest') {
  define('TEST_PATH', dirname(__FILE__) . '/../../simpletest/');
  require_once(TEST_PATH . 'autorun.php');
  
  class CollabFiltrmTest extends UnitTestCase {
    // patch up differences between PHPUnit and SimpleTest
    public function __call($name, $arguments) {
      if ($name == 'assertEquals')    $name = 'assertEqual';
      if ($name == 'assertNotEquals') $name = 'assertNotEqual';
    
      return call_user_func_array(array('parent', $name), $arguments);
    }
  }
}

elseif ($TEST_BED == 'PHPUnit') {
  require_once 'PHPUnit/Framework.php';

  class CollabFiltrTest extends PHPUnit_Framework_TestCase {
    // patch up differences between PHPUnit and SimpleTest
    public function pass() { return; }
  }

  class TestSuite { }
}

else {
  die('Unknown test bed, $TEST_BED must be SimpleTest or PHPUnit');
}