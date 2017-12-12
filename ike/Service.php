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
    protected $inputParameters;

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
        $this->inputParameters = [];
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

    /**
     * Perform a pre-check using a context (for example for CSRF verification)
     * @param Context $ctx the current context
     * @return bool
     */
    protected function precheck(Context $ctx) : bool
    {
        return true;
    }

    /**
     * Check if a service is bootable (a candidate)
     * @param Context $ctx the current context
     * @return bool
     */
    public function isBootable(Context $ctx) : bool
    {
        if ($ctx->matchWith($this->route) && $this->precheck($ctx)) {
            $mixed = $ctx->coersParameters($this->parameters);
            if ($mixed[0]) {
                $this->inputParameters = $mixed[1];
                return true;
            }
        }
        $this->inputParameters = [];
        return false;
    }
}
