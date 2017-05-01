<?php

namespace Maneuver;

use Maneuver\Models\Post;

abstract class ModelFactory {

  public static function create(\stdClass $data) {
    $post_type = $data->type;

    $class_namespace = '\\Maneuver\\Models\\';
    $class_name = $class_namespace . ucfirst($post_type);

    if (!class_exists($class_name)) {
      // Fallback to a basic Post.
      // Will be the case for custom post types.
      $class_name = $class_namespace . 'Post';
    }

    $post = new $class_name();
    $post = self::cast($post, $data);

    return $post;
  }

  private static function cast($destination, $sourceObject)
  {
    $sourceReflection = new \ReflectionObject($sourceObject);
    $destinationReflection = new \ReflectionObject($destination);
    $sourceProperties = $sourceReflection->getProperties();
    foreach ($sourceProperties as $sourceProperty) {
      $sourceProperty->setAccessible(true);
      $name = $sourceProperty->getName();
      $value = $sourceProperty->getValue($sourceObject);
      if ($destinationReflection->hasProperty($name)) {
        $propDest = $destinationReflection->getProperty($name);
        $propDest->setAccessible(true);
        $propDest->setValue($destination,$value);
      } else {
        $destination->$name = $value;
      }
    }
    return $destination;
  }
  
}