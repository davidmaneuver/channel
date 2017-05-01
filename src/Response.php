<?php

namespace Maneuver;

class Response {

  private $channel;
  private $statusCode;
  private $data = [];

  public function __construct(Channel $channel, \GuzzleHttp\Psr7\Response $response) {
    $this->channel = $channel;
    $this->statusCode = $response->getStatusCode();
    
    $this->parseData((string) $response->getBody(), $channel);
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

      if ($body) {
        if (is_object($body)) {
          // Some calls return an object and not an array.
          $body = (array) $body;
        }
        if (is_array($body)) {

          // Convert objects to correct model instances.
          // NOTE: is it overkill to do the entire logic for every item in the list?
          $data = array_map([$this, 'createModel'], $body);
        }
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
  private function createModel($object) {
    $model = ModelFactory::create($object);
    $model->setChannel($this->channel);
    return $model;
  }
}