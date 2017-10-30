<?php

namespace Maneuver\Models;

use Maneuver\ModelFactory;

class Base {

  protected $channel;
  protected $rendered_props = [];

  /**
   * Magic getter.
   * 
   * @since 1.0.0
   */
  public function __get($prop) {
    if (in_array($prop, $this->rendered_props)) {
      return $this->{'post_' . $prop}->rendered;
    }
    if (property_exists($this->custom, $prop)) {
      $val = $this->custom->$prop;
      $was_object = false;

      if (is_object($val)) {
        $val = [$val];
        $was_object = true;
      }

      if (is_array($val)) {
        foreach($val as &$object) {
          if (is_object($object)) {
            $model = ModelFactory::create($object, $this->channel->getCustomClasses());
            $model->setChannel($this->channel);
            $object = $model;
          }
        }
      }

      return $was_object ? reset($val) : $val;
    }
  }

  /**
   * Magic setter.
   * 
   * @since 1.0.0
   */
  public function __set($prop, $value) {
    if (in_array($prop, $this->rendered_props)) {
      $this->{'post_' . $prop} = $value;
    } else {
      $this->$prop = $value;
    }
  }

  /**
   * Magic isset method.
   * 
   * NOTE: This is necesarry to access all properties inside a Twig template.
   * 
   * @since 1.0.0
   */
  public function __isset($prop) {
    if (in_array($prop, $this->rendered_props)) {
      $prop = "post_" . $prop;
    }
    $isset = property_exists($this, $prop);

    if (!$isset) {
      // Check custom properties
      $isset = property_exists($this->custom, $prop);
    }

    return $isset;
  }

  /**
   * Set the channel for this model.
   * Used to retrieve extra content using the same channel options.
   * 
   * @since 1.0.0
   */
  public function setChannel($channel) {
    if ($this->channel) {
      throw new ErrorException("Can't change the channel of a model once it is set.");
    }
    $this->channel = $channel;
  }

  public function next() {
    // TODO
  }

  public function previous() {
    // TODO
  }
}