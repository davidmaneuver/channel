<?php

namespace Maneuver\Models;

abstract class Base {

  protected $rendered_props = ['title', 'content', 'excerpt'];

  public function __get($prop) {
    if (in_array($prop, $this->rendered_props)) {
      return $this->{'post_' . $prop}->rendered;
    }
  }

  public function __set($prop, $value) {
    if (in_array($prop, $this->rendered_props)) {
      $this->{'post_' . $prop} = $value;
    } else {
      $this->$prop = $value;
    }
  }
}