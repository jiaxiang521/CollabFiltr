<?php

require_once(dirname(__FILE__) . '/../settings.php');
require_once(dirname(__FILE__) . '/../../DataSet/FileDataSet.class.php');

define('DATA_FILE', dirname(__FILE__) . '/../../../100k.data');

  // only load file data once (PHPUnit)
class MovieLens100kTest extends CollabFiltrTestSuite {
  public static function suite() {
    return new MovieLens100kTest('MovieLens100kTestTest');
  }

  public function setUp() {
    // shared fixture as loading so much data is a big operation,
    // and doing it every test slows the tests down to a crawl
    $this->sharedFixture = new FileDataSet(DATA_FILE);
  }

  public function tearDown() {
    $this->sharedFixture = NULL;
  }
}
  
class MovieLens100kTestTest extends CollabFiltrTest {
  // only load file data once (SimpleTest)
  public function __construct() {
    global $TEST_BED;

    if ($TEST_BED == 'SimpleTest')
      $this->sharedFixture = new FileDataSet(DATA_FILE);
  }
 
  public function testUserCount() {
    $this->assertEquals($this->sharedFixture->getNumUsers(), 943);
  }
  
  public function testItemCount() {
    $this->assertEquals($this->sharedFixture->getNumItems(), 1682);
  }
  
  public function testIsUserTrue() {
    $this->assertTrue($this->sharedFixture->isUser(404));
  }
  
  public function testIsUserFalse() {
    $this->assertFalse($this->sharedFixture->isUser(1337));
  }
  
  public function testIsItemTrue() {
    $this->assertTrue($this->sharedFixture->isItem(42));
  }
  
  public function testIsItemFalse() {
    $this->assertFalse($this->sharedFixture->isItem(9999));
  }
  
  public function testUserIds() {
    $userIds = $this->sharedFixture->getUserIds();
    sort($userIds, SORT_NUMERIC);
    
    $i = 0;
    foreach ($userIds as $id)
      if ($id != ++$i)
        $this->fail();

    $this->assertEquals($i, 943);
  }
  
  public function testItemIds() {
    $itemIds = $this->sharedFixture->getItemIds();
    sort($itemIds, SORT_NUMERIC);
    
    $i = 0;
    foreach ($itemIds as $id)
      if ($id != ++$i)
        $this->fail();

    $this->assertEquals($i, 1682);
  }
}