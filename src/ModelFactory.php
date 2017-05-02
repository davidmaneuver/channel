<?php

namespace Maneuver;

use Maneuver\Models\Post;

abstract class ModelFactory {

  public static function create(\stdClass $data, $customClass = null) {
    // var_dump($data);exit;
    $class = self::findClass($data, $customClass);
    $post = new $class();
    $post = self::cast($post, $data);

    return $post;
  }

  private static function findClass($data, $customClass = null) {
    $namespace = '\\Maneuver\\Models\\';
    $fallback = 'Base';
    $name = '';
    $type = '';

    // Determine the type of the object.
    if (isset($data->avatar_urls)) {
      $type = 'user';
    }
    if (isset($data->media_type)) {
      $type = 'media';
    }
    if (isset($data->hierarchical) && isset($data->types)) {
      $type = 'taxonomy';
    }
    if (isset($data->hierarchical) && isset($data->taxonomies)) {
      $type = 'posttype';
    }
    if (isset($data->taxonomy)) {
      $type = 'term';
    }
    if (isset($data->type) && $data->type == 'page') {
      $type = 'page';
    }

    // Set classname based on the type.
    switch ($type) {
      case 'user':
        $name = 'User';
        break;
      case 'term':
        $name = 'Term';
        break;
      case 'taxonomy':
        $name = 'Taxonomy';
        break;
      case 'media':
        $name = 'Attachment';
        break;
      case 'page':
        $name = 'Page';
        break;
      case 'posttype':
        $name = 'PostType';
        break;
      default:
        $name = $fallback;
    }

    // Check for custom class.
    if ($customClass) {
      if (is_array($customClass)) {
        if (array_key_exists($type, $customClass)) {
          $class = $customClass[$type];
        }
      } else {
        $class = $customClass;
      }
    }

    if (!isset($class)) {
      $class = $namespace . $name;
    }

    // Make sure the class exists.
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