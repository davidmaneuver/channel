<?php 

namespace Maneuver;

/**
*  Main class
* 
* @since 1.0.0
*/
class Channel {

  protected $auth;

  public function __construct($options) {

    if (is_array($options)) {
      $this->initFromArray($options);
    }
  }

  protected function initFromArray($options) {
    if (isset($options['username']) && isset($options['password'])) {
      // TODO: handle basic auth
    }

    if (isset($options['api_token'])) {
      // TODO: handle api token auth
    }
  }

  public function request($params) {
    if (is_array($params)) {
      // TODO: create string from array. or does Guzzle do this?
      $params = 'url';
    }

    if (is_string($params)) {
      return $this->requestRaw($params);
    }
  }

  protected function requestRaw($endpoint) {
    // TODO: make request with Guzzle.
    return "raw request";
  }

}