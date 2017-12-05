<?php
declare (strict_types=1);

namespace ike;

/**
 * Define the HTTP's context of a session
 */
class Context {

  protected $code;
  protected $method;
  protected $path;
  protected $parameters;
  protected $csrfToken;

  /**
   * Build a context of a session
   */
  public function __construct() {
    $this->code = \http_response_code();
    $this->method = util\tokenize($_SERVER['REQUEST_METHOD']);
    $this->path = $this->computePath();
    $this->parameters = [];
    $this->parameters['get'] = $_GET;
    $this->parameters['post'] = $_POST;
    $this->parameters['input'] = $this->computeInputParameters();
    $this->generateCSRFToken();
  }

  /**
   * Compute the current path of the application
   */
  private function computePath() : string {
    $base = pathinfo($_SERVER['SCRIPT_NAME'])['dirname'];
    $head = explode('?', $_SERVER['REQUEST_URI'])[0];
    return substr($head, strlen($base)+1);
  }

  /**
   * extract the current "input parameters"
   */
  private function computeInputParameters() : array {
    parse_str(file_get_contents('php://input'), $input);
    return $input;
  }

  /**
   * Retreive the parameters for a specific verb
   */
   public function parameters(string $key) : array {
     $formattedKey = util\tokenize($key);
     if($formattedKey === 'get' || $formattedKey === 'post') {
       return $this->parameters[$formattedKey];
     } return $this->parameters['input'];
   }

   /**
    * Get parameters for the current method
    */
    public function currentParameters() : array {
      return $this->parameters($this->method);
    }

    /**
     * Generate a CSRFToken
     */
     private function generateCSRFToken() {
       if(empty($_SESSION['ike.__csrf__'])) {
         $_SESSION['ike.__csrf__'] = util\securityToken();
       }
       $this->csrfToken = $_SESSION['ike.__csrf__'];
     }

}
