<?php

require_once(dirname(__FILE__) . '/../Test/Mock/MockDataSet.class.php');

class DemoDataSet extends MockDataSet {
  public function __construct() {
    $this->_users = array('Lisa Rose' => array('Lady in the Water'  => 2.5,
                                               'Superman Returns'   => 3.5,
                                               'You, Me and Dupree' => 2.5),
                                               
                          'Gene Seymour' => array('Lady in the Water'  => 3.0,
                                                  'Snakes on a Plane'  => 3.5,
                                                  'The Night Listener' => 3.0,
                                                  'You, Me and Dupree' => 3.5),
                                                  
                          'Michael Phillips' => array('Lady in the Water'  => 2.5,
                                                      'Snakes on a Plane'  => 3.0,
                                                      'Superman Returns'   => 3.5,
                                                      'The Night Listener' => 4.0),
                          
                          'Claudia Puig' => array('Snakes on a Plane'  => 3.5,
                                                  'Just My Luck'       => 3.0,
                                                  'Superman Returns'   => 4.0, 
                                                  'You, Me and Dupree' => 2.5),
                          
                          'Mick LaSalle' => array('Lady in the Water'  => 3.0,
                                                  'Snakes on a Plane'  => 4.0, 
                                                  'Just My Luck'       => 2.0,
                                                  'The Night Listener' => 3.0,
                                                  'You, Me and Dupree' => 2.0), 
                          
                          'Jack Matthews' => array('Lady in the Water'  => 3.0,
                                                   'Snakes on a Plane'  => 4.0,
                                                   'The Night Listener' => 3.0,
                                                   'Superman Returns'   => 5.0),
                          
                          'Toby' => array('Superman Returns' => 2.0,
                                          'Gladiator'        => 5.0));
  }
}