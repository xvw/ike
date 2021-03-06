<?php
declare(strict_types=1);

namespace ike;

/**
 * Define a Route
 */

class Route
{
    protected $path;
    protected $raw;
    protected $variables;
    protected $wildcard;

    /**
     * Build a route
     * @param string $path the path of the route
     */
    public function __construct(string $path)
    {
        $this->buildPath($path);
    }

    /**
     * Build the real path of a Route
     * @param string $path the path of the route
     */
    private function buildPath(string $path)
    {
        $this->raw = $path;
        $this->variables = [];
        $this->path = [];
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
    private function buildComplexPath()
    {
        $reg   = '/(\{.+?\})/';
        $flags = PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE;
        $members = preg_split($reg, $this->raw, -1, $flags);
        foreach ($members as $member) {
            if (!$this->memberIsVariable($member)) {
                $this->path[] = ['plain', $member];
            } else {
                $this->buildInternalVariable($member);
            }
        }
    }

    /**
     * Check if a Path member is a variable
     * @param string $elt the fragment of the path
     * @return bool
     */
    private function memberIsVariable(string $elt) : bool
    {
        return strlen($elt) > 0 && $elt[0] == '{' && $elt[strlen($elt)-1] == '}';
    }

    /**
     * Build an URL variable
     * @param string $member the fragment of the path
     */
    private function buildInternalVariable(string $member)
    {
        if (preg_match('/\{(.+?)\}/', $member, $match)) {
            $capture = $match[1];
            $exploded = explode(':', $capture);
            $length = count($exploded);
            if ($length == 1) {
                $this->checkVariableUnicity($capture);
                $this->variables[$capture] = type\regexFor('string');
                $this->path[] = ['variable', $capture];
                return;
            }
            if ($length == 2) {
                $this->checkVariableUnicity($exploded[0]);
                $variableName = $exploded[0];
                $variableRegex = type\regexFor($exploded[1]);
                $this->variables[$variableName] = $variableRegex;
                $this->path[] = ['variable', $variableName];
                return;
            }
        }
        throw new exception\InvalidPathVariable($member);
    }

    /**
     * Check if a variable is unique
     * @param string $key the name of the url variable
     */
    private function checkVariableUnicity(string $key)
    {
        if (\array_key_exists($key, $this->variables)) {
            throw new exception\InvalidPathVariable($key . ' already exists');
        }
    }

    /**
     * Build a regex from a route
     * @return string
     */
    public function toRegex() : string
    {
        $result = "#^";
        foreach ($this->path as $path) {
            if ($path[0] === 'plain') {
                $result .= $path[1];
            } else {
                $result .= $this->variables[$path[1]];
            }
        }
        return $result . "$#";
    }

    /**
     * Check if a route is according an input (uri)
     * @param string $uri the input's uri
     * @return bool
     */
    public function isAccordingTo(string $uri) : bool
    {
        return $this->wildcard || \preg_match($this->toRegex(), $uri) !== 0;
    }

    /**
     * Build a link according a route
     * @param array $input the parameter input
     * @return string
     */
    public function link(array $input) : string
    {
        $result = '/';
        if ($this->wildcard) {
            return util\uniqId('/ike', 'tmp-endpoint.html');
        }
        foreach ($this->path as $path) {
            if ($path[0] === 'plain') {
                $result .= $path[1];
            } else {
                if (\array_key_exists($path[1], $input)) {
                    $str = (string) $input[$path[1]];
                    $reg = '#^'.$this->variables[$path[1]].'$#';
                    if (\preg_match($reg, $str) !== 0) {
                        $result .= $str;
                    } else {
                        $msg = '['.$path[1].'] is not coercable';
                        throw new exception\InvalidPathVariable($msg);
                    }
                } else {
                    throw new exception\MissingField($path[1]);
                }
            }
        }
        return $result;
    }
}
