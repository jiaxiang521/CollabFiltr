<?php

require_once(dirname(__FILE__) . '/FileDataSet.class.php');
require_once(dirname(__FILE__) . '/../Model/GenericRating.class.php');

class MovieLensDataSet extends FileDataSet {
  protected $_dataSet;
  protected $_sample;

  public function __construct($dir, $sampleSize = null) {
    if (file_exists($dir . '/ratings.dat')) {
      $file = array();
      $file['ratings'] = $dir . '/ratings.dat';
      $file['movies']  = $dir . '/movies.dat';
      $file['users']   = $dir . '/users.dat';

      $type = 'large';
    }
    else {
      $file = $dir . '/100k.data';
      $type = 'small';
    }

    $builder = new GenericDataSetBuilder(array(1,5));

    if ($type == 'large') {
      $movies = array();
      $doneMovies = array();

      $fh = fopen($file['users'], 'rb');
      if (!$fh) throw new RuntimeException('Could not open users file');

      while (!feof($fh) && $line = fgets($fh)) {
        list($userId) = explode('::', $line);
        $builder->loadLine($userId, null, null);
      }
      fclose($fh);

      $fh = fopen($file['movies'], 'rb');
      if (!$fh) throw new RuntimeException('Could not open users file');

      while (!feof($fh) && $line = fgets($fh)) {
        list($itemId) = explode('::', trim($line));
        $builder->loadLine(null, $itemId, null);

        $movies[] = $itemId;
      }
      fclose($fh);
    }

    $fh = fopen($type == 'large' ? $file['ratings'] : $file, 'rb');
    if (!$fh) throw new RuntimeException('Could not open file');

    // generate array of ids we're using for the sample
    if ($sampleSize) {
      $lines = $this->_countLines($file);

      $sample = array();
      $this->_sample = array();

      if ($sampleSize > $lines)
        throw new InvalidArgumentException('Test sample larger than data set');

      $sampleLines = array();

      while (count($sampleLines) < $sampleSize) {
        $num = mt_rand(1, $lines);

        if (!isset($sampleLines[$num]))
          $sampleLines[$num] = 1;
      }
    }

    // add each line at a time to builder
    // (using a builder as GenericDataSet is immutable)
    $i = 0;
    while (!feof($fh) && $line = fgets($fh)) {
      if ($type == 'large')
        list($userId, $itemId, $rating) = explode('::', $line);
      else
        list($userId, $itemId, $rating) = explode("\t", $line);

      if (isset($sampleLines[++$i])) {
        $this->_sample[] = new GenericRating($userId, $itemId, $rating);
        continue;
      }

      $builder->loadLine($userId, $itemId, $rating);

      if ($type == 'large')
        $doneMovies[$itemId] = 1;
    }

    fclose($fh);
    unset($sampleLines);

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