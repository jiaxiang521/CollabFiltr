<?php

require_once(dirname(__FILE__) . '/../settings.php');
require_once(dirname(__FILE__) . '/../../DataSet/MovieLensDataSet.class.php');

define('DATA_DIR', dirname(__FILE__) . '/../../Data/MovieLens/small');

// only load file data once (PHPUnit)
class MovieLens100kTest extends CollabFiltrTestSuite {
  public static function suite() {
    return new MovieLens100kTest('MovieLens100kTestTest');
  }

  public function setUp() {
    // shared fixture as loading so much data is a big operation,
    // and doing it every test slows the tests down to a crawl
    $this->sharedFixture = new MovieLensDataSet(DATA_DIR);
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
      $this->sharedFixture = new MovieLensDataSet(DATA_DIR);
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
    $this->assertFalse($this->sharedFixture->isUser(9999));
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
  
  public function testUserObjError() {
    try { 
      $this->sharedFixture->getUser(9999);
      $this->fail('User should have been invalid');
    } catch (Exception $e) {
      return $this->pass();
    }
  }
  
  public function testUserObjId() {
    $user = $this->sharedFixture->getUser(666);
    
    $this->assertEquals($user->getId(), 666);
  }
  
  public function testUserObjRatings() {
    $user    = $this->sharedFixture->getUser(666);
    $rated   = $this->sharedFixture->getUserRatingsArray(666);
    $ratings = array();
    
    foreach ($rated as $itemId => $rating)
      $ratings[] = new GenericRating(666, $itemId, $rating); 
    
    $this->assertEquals($user->getRatings(), $ratings);
  }
}