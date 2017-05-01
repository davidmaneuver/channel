<?php

namespace Maneuver;

use Maneuver\Models\Post;

abstract class ModelFactory {

  public static function create(\stdClass $data) {
    // var_dump($data);exit;
    $class = self::findClass($data);
    $post = new $class();
    $post = self::cast($post, $data);

    return $post;
  }

  private static function findClass($data) {
    $namespace = '\\Maneuver\\Models\\';
    $fallback = 'Post';
    $name = '';

    if (isset($data->avatar_urls)) {
      $name = 'User';
    }

    if (isset($data->media_type)) {
      $name = 'Attachment';
    }

    if (isset($data->hierarchical)) {
      $name = 'Taxonomy';
    }

    if (isset($data->taxonomy)) {
      $name = 'Term';
    }

    if (isset($data->type)) {
      $name = ucfirst($data->type);
    }

    $class = $namespace . $name;

    if (!class_exists($class)) {
      // Fallback to a basic Post.
      // Will be the case for custom post types.
      $class = $namespace . $fallback;
    }

    return $class;
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