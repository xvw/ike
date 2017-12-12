<?php
declare(strict_types=1);

namespace ike;

/**
 * Define a Service
 */
class Service
{
    protected $method;
    protected $route;
    protected $parameters;

    /**
     * Build a Service
     * @param string $method the method of the service
     * @param string $path the path to build a route
     * @param string $params the parameters
     */
    public function __construct(string $method, string $path, array $params)
    {
        $this->method = util\validHttpMethod($method);
        $this->route = new Route($path);
        $this->parameters = $params;
    }
}
