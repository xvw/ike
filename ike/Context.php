<?php
declare(strict_types=1);

namespace ike;

/**
 * Define the HTTP's context of a session
 */
class Context
{
    protected $code;
    protected $method;
    protected $path;
    protected $parameters;
    protected $csrfToken;

    /**
     * Build a context of a session
     */
    public function __construct()
    {
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
     * @return string
     */
    private function computePath() : string
    {
        $base = pathinfo($_SERVER['SCRIPT_NAME'])['dirname'];
        $head = explode('?', $_SERVER['REQUEST_URI'])[0];
        return substr($head, strlen($base)+1);
    }

    /**
     * extract the current "input parameters"
     * @return array
     */
    private function computeInputParameters() : array
    {
        parse_str(file_get_contents('php://input'), $input);
        return $input;
    }

    /**
     * Retreive the parameters for a specific verb
     * @return array
     */
    public function parameters(string $key) : array
    {
        $formattedKey = util\tokenize($key);
        if ($formattedKey === 'get' || $formattedKey === 'post') {
            return $this->parameters[$formattedKey];
        }
        return $this->parameters['input'];
    }

    /**
     * Get parameters for the current method
     * @return array
     */
    public function currentParameters() : array
    {
        return $this->parameters($this->method);
    }

    /**
     * Generate a CSRFToken
     */
    private function generateCSRFToken()
    {
        if (empty($_SESSION['ike.__csrf__'])) {
            $_SESSION['ike.__csrf__'] = util\securityToken();
        }
        $this->csrfToken = $_SESSION['ike.__csrf__'];
    }

    /**
     * Check if a context is according to a route
     * @param Route $route the checked route
     * @return bool
     */
    public function matchWith(Route $route) : bool
    {
        return $route->isAccordingTo($this->path);
    }
}
