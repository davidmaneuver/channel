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

  /**
   * Make a request to the REST API.
   * 
   * @since 1.0.0
   */
  public function request($params) {
    if (is_array($params)) {
      // TODO: create string from array. or does Guzzle do this?
      $params = 'url';
    }

    if (is_string($params)) {
      return $this->doRequest($params);
    }
  }

  /**
   * Makes the actual request and returns a Response with data.
   * 
   * @since 1.0.0
   */
  protected function doRequest($endpoint, $args = []) {
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

    $res = $client->request('GET', $endpoint, $requestOptions);
    return new Response($res);
  }

}