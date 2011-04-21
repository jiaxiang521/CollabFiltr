<?php

require_once(dirname(__FILE__) . '/FileDataSet.class.php');
require_once(dirname(__FILE__) . '/../Model/GenericRating.class.php');

class JesterDataSet extends FileDataSet {
  protected $_dataSet;
  protected $_sample;

  public function __construct($file, $sampleSize = null, $sampleIds = null) {
    $builder = new GenericDataSetBuilder(array(-10.0, +10.0));
    
    $fh = fopen($file, 'rb');
    if (!$fh) throw new RuntimeException('Could not open file');
    
    $sampleIds = array_combine(array_values($sampleIds), array_fill(0, count($sampleIds), 1));
    $sampleDone = array();
    
    // add each line at a time to builder
    // (using a builder as GenericDataSet is immutable)
    $i = 0;
    while (!feof($fh) && $line = fgets($fh)) {
      list($userId, $itemId, $rating) = explode("\t\t", $line);
      
      if (is_array($sampleIds) && isset($sampleIds[$userId])) {
        if (isset($sampleDone[$userId])) {
          $this->_sample[] = new GenericRating($userId, $itemId, $rating);
          continue; 
        }
        
        $sampleDone[$userId] = true;
      }

      $builder->loadLine($userId, $itemId, $rating);
    }
    
    fclose($fh);
    $this->_dataSet = $builder->build();
  }

  public function getSample() { return $this->_sample; }

  private function _countLines($file) {
    $fhTmp  = fopen($file, 'rb');
    $lines  = 0;

    while (!feof($fhTmp))
      if (fgets($fhTmp)) $lines++;
    fclose($fhTmp);

    return $lines;
  }
}