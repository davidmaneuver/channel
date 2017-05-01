<?php 

namespace Maneuver\Auth;

/**
*  Authentication class
* 
* @since 1.0.0
*/
abstract class Auth {

  /**
   * Factory class to return the correct auth subclass.
   * 
   * @since 1.0.0
   */
  public static function create() {
    $args = func_get_args();

    if (count($args) === 0) {
      return new Cookie();
    }

    if (count($args) === 1) {
      return new Token($args[0]);
    }

    if (count($args) === 2) {
      return new Basic($args[0], $args[1]);
    }

  }

  /**
   * Set appropriate headers for the api request call.
   * Should be overridden in a subclass.
   * 
   * @since 1.0.0
   */
  public function setRequestHeaders(&$headers) {}

}