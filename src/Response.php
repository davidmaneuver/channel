<?php

namespace Maneuver;

class Response {

  private $statusCode;
  private $data = [];

  public function __construct(\GuzzleHttp\Psr7\Response $response) {
    $this->statusCode = $response->getStatusCode();
    
    $this->parseData((string) $response->getBody());
  }

  /**
   * Getter for the status code.
   * 
   * @since 1.0.0
   */
  public function getStatusCode() {
    return $this->statusCode;
  }

  /**
   * Getter for the data array.
   * 
   * @since 1.0.0
   */
  public function getData() {
    return $this->data;
  }

  /**
   * Checks the response body and creates models for each object.
   * 
   * @since 1.0.0
   */
  private function parseData($body) {
    $data = null;

    if (!empty($body) && is_string($body)) {
      $body = json_decode($body);

      if ($body && is_array($body)) {
        $data = array_map([$this, 'createPost'], $body);
      }
    }

    if ($data) {
      $this->data = $data;
    }
  }

  /**
   * Constructs a model for an object.
   * 
   * @since 1.0.0
   */
  private function createPost($object) {
    return ModelFactory::create($object);
  }
}