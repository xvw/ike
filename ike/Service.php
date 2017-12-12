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
        $this->setParameters($params);
    }

    /**
     * Set the parameters with verification
     * @param array $params the HTTP's parameters
     * @throws \ike\exception\InvalidHTTPType
     */
    private function setParameters(array $params)
    {
        $this->parameters = [];
        foreach ($params as $key => $type) {
            \ike\type\validHTTPType($this->method, $key, $type);
            $this->parameters[$key] = $type;
        }
    }
}
