<?php

require_once(dirname(__FILE__) . '/../../Interfaces/UserSimilarity.interface.php');

class MockSimilarity implements UserSimilarity {
  private $_type;
  
  public function __construct($type = 'pearson') {
    switch ($type) {
      case 'euclidean':
        $this->_type = 'euclidean';
        break;

      case 'pearson':
        $this->_type = 'pearson';
        break;
        
      default:
        throw new InvalidArgumentException('Type not implemented');
    }
  }
  
  public function userSimilarity($userId1, $userId2) {
    switch ($this->_type) {
      case 'euclidean':
        $similarity = &$this->_euclidean;
        break;
    
      case 'pearson':
      default:
        $similarity = &$this->_pearson;
    }
  
    if (!isset($similarity[$userId1]) || !isset($similarity[$userId1][$userId2]))
      throw new InvalidArgumentException('Invalid users');
      
     return $similarity[$userId1][$userId2];
  }
  
  private $_pearson = array(
    'Lisa Rose' => array('Gene Seymour'     => 0.39605901719067,
                         'Michael Phillips' => 0.40451991747795,
                         'Claudia Puig'     => 0.56694670951384,
                         'Mick LaSalle'     => 0.594088525786,
                         'Jack Matthews'    => 0.747017880834,
                         'Toby'             => 0.99124070716193),
                     
    'Gene Seymour' => array('Lisa Rose'        => 0.39605901719067,
                            'Michael Phillips' => 0.20459830184114,
                            'Claudia Puig'     => 0.31497039417436,
                            'Mick LaSalle'     => 0.41176470588235,
                            'Jack Matthews'    => 0.96379568187564,
                           'Toby'              => 0.38124642583151),
                          
    'Michael Phillips' => array('Lisa Rose'     => 0.40451991747795,
                                'Gene Seymour'  => 0.20459830184114,
                                'Claudia Puig'  => 1,
                                'Mick LaSalle'  => -0.25819888974716,
                                'Jack Matthews' => 0.13483997249265,
                                'Toby'          => -1),
          
    'Claudia Puig' => array('Lisa Rose'        => 0.56694670951384,
                            'Gene Seymour'     => 0.31497039417436,
                            'Michael Phillips' => 1,
                            'Mick LaSalle'     => 0.56694670951384,
                            'Jack Matthews'    => 0.028571428571429,
                            'Toby'             => 0.89340514744156),

    'Mick LaSalle' => array('Lisa Rose'        => 0.594088525786,
                            'Gene Seymour'     => 0.41176470588235,
                            'Michael Phillips' => -0.25819888974716,
                            'Claudia Puig'     => 0.56694670951384,
                            'Jack Matthews'    => 0.21128856368213,
                            'Toby'             => 0.9244734516419),
                            
    'Jack Matthews' => array('Lisa Rose'       => 0.747017880834,
                             'Gene Seymour'     => 0.96379568187564,
                             'Michael Phillips' => 0.13483997249265,
                             'Claudia Puig'     => 0.028571428571429,
                             'Mick LaSalle'     => 0.21128856368213,
                             'Toby'             => 0.66284898035987),
    
    'Toby' => array('Lisa Rose'        => 0.99124070716193,
                    'Gene Seymour'     => 0.38124642583151,
                    'Michael Phillips' => -1,
                    'Claudia Puig'     => 0.89340514744156,
                    'Mick LaSalle'     => 0.9244734516419,
                    'Jack Matthews'    => 0.66284898035987),
  );
  
  private $_euclidean = array(
    'Lisa Rose' => array('Gene Seymour'     => 0.14814814814815,
                         'Michael Phillips' => 0.44444444444444,
                         'Claudia Puig'     => 0.28571428571429,
                         'Mick LaSalle'     => 0.33333333333333,
                         'Jack Matthews'    => 0.21052631578947,
                         'Toby'             => 0.22222222222222),
                         
    'Gene Seymour' => array('Lisa Rose'        => 0.14814814814815,
                            'Michael Phillips' => 0.21052631578947,
                            'Claudia Puig'     => 0.13333333333333,
                            'Mick LaSalle'     => 0.12903225806452,
                            'Jack Matthews'    => 0.8,
                            'Toby'             => 0.10810810810811),
    
    'Michael Phillips' => array('Lisa Rose'     => 0.44444444444444,
                                'Gene Seymour'  => 0.21052631578947,
                                'Claudia Puig'  => 0.57142857142857,
                                'Mick LaSalle'  => 0.28571428571429,
                                'Jack Matthews' => 0.18181818181818,
                                'Toby'          => 0.28571428571429),
                                
    'Claudia Puig' => array('Lisa Rose'        => 0.28571428571429,
                            'Gene Seymour'     => 0.13333333333333,
                            'Michael Phillips' => 0.57142857142857,
                            'Mick LaSalle'     => 0.17391304347826,
                            'Jack Matthews'    => 0.18181818181818,
                            'Toby'             => 0.23529411764706),
                            
    'Mick LaSalle' => array('Lisa Rose'        => 0.33333333333333,
                            'Gene Seymour'     => 0.12903225806452,
                            'Michael Phillips' => 0.28571428571429,
                            'Claudia Puig'     => 0.17391304347826,
                            'Jack Matthews'    => 0.13793103448276,
                            'Toby'             => 0.30769230769231),
                            
    'Jack Matthews' => array('Lisa Rose'        => 0.21052631578947,
                             'Gene Seymour'     => 0.8,
                             'Michael Phillips' => 0.18181818181818,
                             'Claudia Puig'     => 0.18181818181818,
                             'Mick LaSalle'     => 0.13793103448276,
                             'Toby'             => 0.11764705882353),
                             
    'Toby' => array('Lisa Rose'        => 0.22222222222222,
                    'Gene Seymour'     => 0.10810810810811,
                    'Michael Phillips' => 0.28571428571429,
                    'Claudia Puig'     => 0.23529411764706,
                    'Mick LaSalle'     => 0.30769230769231,
                    'Jack Matthews'    => 0.11764705882353),
  );
}