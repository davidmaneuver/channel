<?php 

class ChannelTest extends PHPUnit_Framework_TestCase {
	
  /**
  * Syntax error test.
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testIsThereAnySyntaxError(){
  	$var = new Maneuver\Channel();
  	$this->assertTrue(is_object($var));
  	unset($var);
  }
  
}