<?php 

ini_set('display_errors', 0);

class ChannelTest extends PHPUnit_Framework_TestCase {
	
  /**
  * Syntax error test.
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testIsThereAnySyntaxError(){
  	$var = new Maneuver\Channel(['uri' => '/']);
  	$this->assertTrue(is_object($var));
  }

  public function testRequest() {
    $c = new Maneuver\Channel(['uri' => 'http://example.com/']);
    $r = $c->request('posts');
    $this->assertInstanceOf(Maneuver\Response::class, $r);
  }

  public function testNoUriException() {
    $this->expectException(ErrorException::class);
    new Maneuver\Channel([]);
  }
  
}