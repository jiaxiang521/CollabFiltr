<?php

require_once(dirname(__FILE__) . '/../settings.php');
require_once(dirname(__FILE__) . '/../../DataSet/MovieLensDataSetDatabase.class.php');

class MovieLensFullDBTest extends CollabFiltrTestSuite {
  public static function suite() {
    return new MovieLensFullDBTest('MovieLensFullDBTestTest');
  }

  public function setUp() {
    $this->sharedFixture = new MovieLensDataSetDatabase(DB_USER, DB_PASS, DB_SOCK, DB_NAME);
  }

  public function tearDown() {
    $this->sharedFixture = NULL;
  }
}
  
class MovieLensFullDBTestTest extends CollabFiltrTest {
  public function __construct() {
    global $TEST_BED;
    
    if ($TEST_BED == 'PHPUnit') {
      // Need DB set up for these tests (PHPUnit)
      if (!extension_loaded('pdo_mysql') || !defined('DB_TEST') || !DB_TEST)
        return $this->markTestSkipped('No database to test');
    }
    
    elseif ($TEST_BED == 'SimpleTest') {
      $this->sharedFixture = new MovieLensDataSetDatabase(DB_USER, DB_PASS, DB_SOCK, DB_NAME);
    }
  }
  
  // Need DB set up for these tests (SimpleTest)
  public function skip() {
    $this->skipIf(
      !extension_loaded('pdo_mysql') || !defined('DB_TEST') || !DB_TEST,
      'No database to test'
    );
  }
 
  public function testUserCount() {
    $this->assertEquals($this->sharedFixture->getNumUsers(), 6040);
  }
  
  public function testItemCount() {
    $this->assertEquals($this->sharedFixture->getNumItems(), 3883);
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

    $this->assertEquals($i, 6040);
  }
  
  public function testItemIds() {
    $itemIds = $this->sharedFixture->getItemIds();
    $itemNum = count($itemIds);

    $this->assertTrue($itemNum == 3883 && $itemIds[0] == 1
                      && $itemIds[3882] == 3952);
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
    $user = $this->sharedFixture->getUser(1024);
    
    $this->assertEquals($user->getId(), 1024);
  }
  
  public function testUserObjRatings() {
    $user    = $this->sharedFixture->getUser(1024);
    $rated   = $this->sharedFixture->getUserRatingsArray(1024);
    $ratings = array();
    
    foreach ($rated as $itemId => $rating)
      $ratings[] = new GenericRating(1024, $itemId, $rating); 
    
    $this->assertEquals($user->getRatings(), $ratings);
  }
}