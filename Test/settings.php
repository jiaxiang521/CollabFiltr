<?php

// Path to simpletest
define('TEST_PATH', dirname(__FILE__) . '/../../simpletest/');

// Database options
define('DB_TEST', 1);
define('DB_SOCK', '/Applications/MAMP/tmp/mysql/mysql.sock');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'collabfiltr');

// only SimpleTest an be run directly via PHP CLI or the web
if (isset($argc) || (!isset($_SERVER['argc'])))
  $TEST_BED = 'SimpleTest';
else
  $TEST_BED = 'PHPUnit';
  
if (!isset($TEST_BED))
  die('Unknown test bed, TEST_BED must be SimpleTest or PHPUnit');

if ($TEST_BED == 'SimpleTest') {
  require_once(TEST_PATH . 'autorun.php');
  
  class CollabFiltrTest extends UnitTestCase {
    // patch up differences between PHPUnit and SimpleTest
    
    protected $sharedFixture;
    
    public function __call($name, $arguments) {
      if ($name == 'assertEquals')    $name = 'assertEqual';
      if ($name == 'assertNotEquals') $name = 'assertNotEqual';
    
      return call_user_func_array(array('parent', $name), $arguments);
    }
  }
  
  // has no special meaning to SimpleTest
  class CollabFiltrTestSuite {
    protected $sharedFixture;
  }
}

elseif ($TEST_BED == 'PHPUnit') {
  require_once 'PHPUnit/Framework.php';

  class CollabFiltrTest extends PHPUnit_Framework_TestCase {
    // SimpleTest counts pass(), so mimic this behaviour
    public function pass() { return $this->assertTrue(1 == 1); }
  }
  
  class CollabFiltrTestSuite extends PHPUnit_Framework_TestSuite { }
  class TestSuite { }
}

else {
  die('Unknown test bed, $TEST_BED must be SimpleTest or PHPUnit');
}