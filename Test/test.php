<?php

require_once(dirname(__FILE__) . '/settings.php');

// SimpleTest
class AllTests extends TestSuite {
  function AllTests() {
    $this->TestSuite('CollabFiltr Test Suite');
    
    $directories = getTestDirectories();
    
    // add *.test.php from directory to test bed
    foreach ($directories as $directory)
      foreach (glob($directory . '*.test.php') as $file)
        $this->addFile($file);
  }
}

// PHPUnit
class test {
  public static function suite() {
    $suite = new PHPUnit_Framework_TestSuite('CollabFiltr Test Suite');
    
    $directories = getTestDirectories();
  
    foreach ($directories as $directory) {
      foreach (glob($directory . '*.test.php') as $file) {
        $class = preg_replace('/(.*)\.test\.php/', '\1', basename($file));
 
        require_once($file);
        $suite->addTestSuite($class . 'Test');
      }
    }
    
    PHPUnit_Util_Filter::addFileToFilter('test.php');
    PHPUnit_Util_Filter::addFileToFilter('settings.php');
    
    return $suite;
  }
}

// get directory tree to search for files in
function getTestDirectories() {
  $dirs = glob(dirname(__FILE__) . '*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
  
  for ($i = 0; $i < count($dirs); $i++) {
    $subdirs = glob($dirs[$i] . '*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
    $dirs    = array_merge($dirs, $subdirs);
  }
  
  return $dirs;
}