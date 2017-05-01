<?php

namespace Maneuver\Models;

abstract class Base {

  protected $channel;
  protected $rendered_props = [];

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