<?php
declare (strict_types=1);

namespace ike;

/**
 * Define a Route
 */

class Route {

  protected $method;
  protected $path;
  protected $wildcard;

  /**
   * Build a route
   */
  private function __construct(string $method, string $path) {
    $this->method = util\tokenize($method);
    $this->path = [];
    $this->buildPath($path);
  }

  /**
   * Build the real path of a Route
   */
  private function buildPath(string $path) {
   $this->path['raw'] = $path;
   $this->path['members'] = [];
   $this->path['complex'] = [];
   $this->wildcard = false;
   if ($path === '*') {
     $this->wildcard = true;
     return;
   }
   $this->buildComplexPath();
  }

   /**
    * Build a complexe path
    */
  private function buildComplexPath() {
    $reg   = '/(\{.+?\})/';
    $flags = PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE;
    $members = preg_split($reg, $this->path['raw'], -1, $flags);
    foreach($members as $member) {
      if (!$this->memberIsVariable($member)) {
        $this->path['complex'][] = ['plain', $member];
      } else {
        $this->buildInternalVariable($member);
      }
    }
  }

  /**
   * Check if a Path member is a variable
   */
  private function memberIsVariable(string $elt) : bool {
    return strlen($elt) > 0 && $elt[0] == '{' && $elt[strlen($elt)-1] == '}';
  }

  /**
   * Save variable
   */
  private function buildInternalVariable(string $member) {
    if (preg_match('/\{(.+?)\}/', $member, $match)) {

    }
  }

  /**
   * Build a GET Route
   */
  public static function get(string $path) {
    return new Route('get', $path);
  }

  /**
   * Build a POST Route
   */
  public static function post(string $path) {
    return new Route('post', $path);
  }

  /**
   * Build a PUT Route
   */
  public static function put(string $path) {
    return new Route('put', $path);
  }

  /**
   * Build a DELETE Route
   */
  public static function delete(string $path) {
    return new Route('delete', $path);
  }

}
