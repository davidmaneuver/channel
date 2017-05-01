<?php

namespace Maneuver;

class Response {

  private $statusCode;

  public function __construct(\GuzzleHttp\Psr7\Response $response) {
    $this->statusCode = $response->getStatusCode();
  }
}