<?php 

namespace Maneuver\Auth;

/**
*  Authentication class
* 
* @since 1.0.0
*/
class Token extends Auth {

  private $token;

  public function __construct($token) {
    $this->token = $token;
  }

  public function setRequestHeaders(&$headers) {
    $headers['api-token'] = $this->token;
  }

}