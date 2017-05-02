<?php 

namespace Maneuver;

use Maneuver\Auth\Auth;

/**
*  Main class
* 
* @since 1.0.0
*/
class Channel {

  protected $auth;
  protected $base_uri;
  protected $options = [];
  protected $classmap = [];

  public function __construct(array $options) {
    $this->initFromArray($options);
  }

  /**
   * Initialize the class with an array of options.
   * 
   * @since 1.0.0
   */
  protected function initFromArray(array $options) {
    if (empty($options['uri'])) {
      throw new \ErrorException('URI is required and was not provided');
    }

    $this->base_uri = $options['uri'];
    unset($options['uri']);

    if (isset($options['token'])) {
      // TODO: handle api token auth
      $this->auth = Auth::create($options['token']);
      unset($options['token']);
    } else if (isset($options['username']) && isset($options['password'])) {
      // TODO: handle basic auth
      $this->auth = Auth::create($options['username'], $options['password']);
      unset($options['username']);
      unset($options['password']);
    } else {
      $this->auth = Auth::create();
    }

    $this->options = $options;
  }

  public function setCustomClasses(array $classmap) {
    $this->classmap = $classmap;
  }

  public function getCustomClasses() {
    return $this->classmap;
  }

  /**
   * Make a request to the REST API.
   * 
   * @since 1.0.0
   */
  public function request($endpoint, $params = [], $method = 'GET') {
    return $this->doRequest($endpoint, $params, $method);
  }

  /**
   * Shortcut for the request method.
   * 
   * @since 1.0.0
   */
  public function get($endpoint, $params = []) {
    return $this->request($endpoint, $params);
  }

  /**
   * Makes the actual request and returns a Response with data.
   * 
   * @since 1.0.0
   */
  protected function doRequest($endpoint, $args = [], $method = 'GET') {
    if ($method != 'GET') {
      throw new Exception('Only GET request are supported at the moment.');
    }

    $client = new \GuzzleHttp\Client([
      'base_uri' => $this->base_uri,
    ]);

    $requestOptions = [];
    $requestOptions['http_errors'] = ini_get('display_errors') ? TRUE : FALSE;

    $headers = [];

    $this->auth->setRequestHeaders($headers);

    if (!empty($headers)) {
      $requestOptions['headers'] = $headers;
    }

    $requestOptions = array_merge($requestOptions, $args);

    $res = $client->request($method, $endpoint, $requestOptions);
    return new Response($this, $res);
  }

  /****************/
  /*** ALIASSES ***/
  /****************/

  public function getPosts($args = []) {
    return $this->request("posts", $args)->getData();
  }

  public function getPost($id) {
    return $this->request("posts/$id")->getData();
  }

  public function getPages($args = []) {
    return $this->request("pages", $args)->getData();
  }

  public function getPage($id) {
    return $this->request("pages/$id")->getData();
  }

}