<?php 

namespace Maneuver;

use Maneuver\Auth\Auth;
use Symfony\Component\Cache\Simple\FilesystemCache;

/**
*  Main class
* 
* @since 1.0.0
*/
class Channel {

  protected $auth;
  protected $base_uri;
  protected $classmap = [];
  protected $options = [
    'cache' => true,
  ];

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

    $this->options = array_merge($this->options, $options);
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
    return $this->request($endpoint, $params)->getData();
  }

  /**
   * Makes the actual request and returns a Response.
   * 
   * @since 1.0.0
   */
  protected function doRequest($endpoint, $args = [], $method = 'GET') {
    if ($method != 'GET') {
      throw new Exception('Only GET request are supported at the moment.');
    }

    $cacheKey = md5(implode('|', [
      $endpoint, 
      implode(',', $args), 
      $method]
    ));

    $cache = new FilesystemCache('', 3600, 'cache');

    if (!$this->options['cache'] || !$cache->has($cacheKey)) {

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

      $json = (string)$res->getBody();
      $cache->set($cacheKey, $json);

    } else {
      $json = $cache->get($cacheKey);
      $res = new \GuzzleHttp\Psr7\Response(200, [], $json);
    }

    return new Response($this, $res);
  }

  /****************/
  /*** ALIASSES ***/
  /****************/

  public function getPosts($args = []) {
    if (is_string($args)) {
      return $this->request($args);
    }
    return $this->get("posts", $args);
  }

  public function getPost($id) {
    return $this->get("posts/$id");
  }

  public function getPages($args = []) {
    return $this->get("pages", $args);
  }

  public function getPage($id) {
    return $this->get("pages/$id");
  }

  public function getTaxonomies() {
    return $this->get("taxonomies");
  }

  public function getTaxonomy($slug) {
    return $this->get("taxonomies/$slug");
  }

  public function getUsers() {
    return $this->get('users');
  }

  public function getMedia() {
    return $this->get('media');
  }

  public function getOptions() {
    return $this->get('options');
  }

}