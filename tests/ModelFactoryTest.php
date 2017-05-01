<?php


class ModelFactoryTest extends PHPUnit_Framework_TestCase {

  public function testModelGeneration_User() {
    $data = new stdClass();
    $data->avatar_urls = [];
    $object = \Maneuver\ModelFactory::create($data);
    $this->assertInstanceOf(Maneuver\Models\User::class, $object);
  }

  public function testModelGeneration_Post() {
    $data = new stdClass();
    $object = \Maneuver\ModelFactory::create($data);
    $this->assertInstanceOf(Maneuver\Models\Post::class, $object);
  }

  public function testModelGeneration_Page() {
    $data = new stdClass();
    $data->type = 'page';
    $object = \Maneuver\ModelFactory::create($data);
    $this->assertInstanceOf(Maneuver\Models\Page::class, $object);
  }

  public function testModelGeneration_Attachment() {
    $data = new stdClass();
    $data->media_type = '';
    $object = \Maneuver\ModelFactory::create($data);
    $this->assertInstanceOf(Maneuver\Models\Attachment::class, $object);
  }

  public function testModelGeneration_Taxonomy() {
    $data = new stdClass();
    $data->hierarchical = '';
    $object = \Maneuver\ModelFactory::create($data);
    $this->assertInstanceOf(Maneuver\Models\Taxonomy::class, $object);
  }

  public function testModelGeneration_Term() {
    $data = new stdClass();
    $data->taxonomy = '';
    $object = \Maneuver\ModelFactory::create($data);
    $this->assertInstanceOf(Maneuver\Models\Term::class, $object);
  }
}