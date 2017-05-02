<?php

namespace Maneuver\Models;

class Taxonomy extends Base {

  public function terms() {
    if (isset($this->rest_base) && $this->channel) {
      return $this->channel->request($this->rest_base)->getData();
    }

    return [];
  }
}