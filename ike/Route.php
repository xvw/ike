<?php
declare (strict_types=1);

namespace ike;

/**
 * Define a Route
 */

class Route {

  protected $method;
  protected $rawPath;

  /**
   * Build a route
   */
  private function __construct($method, $path) {
    $this->method = util\tokenize($method);
    $this->rawPath = $path;
  }

  /**
   * Build a GET Route
   */
  public static function get($path) {
    return new Route('get', $path);
  }

  /**
   * Build a POST Route
   */
  public static function post($path) {
    return new Route('post', $path);
  }

  /**
   * Build a PUT Route
   */
  public static function put($path) {
    return new Route('put', $path);
  }

  /**
   * Build a DELETE Route
   */
  public static function delete($path) {
    return new Route('delete', $path);
  }

}
