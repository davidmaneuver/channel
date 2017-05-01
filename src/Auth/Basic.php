<?php 

namespace Maneuver\Auth;

/**
*  Authentication class
* 
* @since 1.0.0
*/
class Basic extends Auth {

  private $username;
  private $password;

  public function __construct($username, $password) {
    $this->username = $username;
    $this->password = $password;
  }

  public function setRequestHeaders(&$headers) {
    $key = $this->username . ':' . $this->password;
    $headers['Authorization'] = 'Basic ' . base64_encode($key);
  }

}