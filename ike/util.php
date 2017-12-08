<?php
declare(strict_types=1);

namespace ike\util;

/**
 * Trim + downcase on a string
 * @param string $str the string to be tokenized
 * @return string
 */
function tokenize(string $str) : string
{
    return strtolower(trim($str));
}


/**
 * Create a "potential" uniq ID.
 * @param string prefix : the prefix of the uniq ID (maybe the tag name)
 * @param string suffix : the suffix of the uniq ID (for more uniqness)
 * @return string A String as a potential uniq ID
 */
function uniqId(string $prefix = '', string $suffix = null) : string
{
    $suffix = $suffix ?? time();
    $prefix = ($prefix === '') ? $prefix : $prefix.'-';
    $suffix = ($suffix === '') ? $suffix : '-'.$suffix;
    return \uniqid($prefix).$suffix;
}
/**
 * Generate a token (for csrftoken, for example)
 * @return string
 */
function securityToken() : string
{
    return bin2hex(openssl_random_pseudo_bytes(32));
}

/**
 * Check the validity of a token
 * @param string $base the hash used as a reference
 * @param string $input the gived hash
 * @return bool
 */
function checkToken(string $base, string $input) : bool
{
    return hash_equals($base, $input);
}

/**
 * Perform a  suggestion for a data based on a container
 * @param string $key the data
 * @param array keys to be matched
 * @return string
 */
function suggestionFor(string $key, array $keys) : string
{
    $len = count($keys);
    if ($len == 0 || strlen($key) < 1) {
        return 'nothing';
    }
    if ($len == 1) {
        return $keys[0];
    }
    $clone = $keys;
    (usort($clone, function ($keyA, $keyB) use ($key) {
        $a = levenshtein($key, $keyA);
        $b = levenshtein($key, $keyB);
        return ($a-$b) ? ($a-$b)/abs($a-$b) : 0;
    }));
    return $clone[0];
}

/**
 * Validate Callback with parameters
 * @param Callable $fun function to be checked
 * @param array $params required parameters
 * @throws exception\InvalidFunction
 * @throws exception\ParameterDoesNotExistsInFunction
 */
function validFunctionParameters(\Closure $function, array $input)
{
    $reflex = new \ReflectionFunction($function);
    $nbparams = $reflex->getNumberOfParameters();
    if ($nbparams !== count($input)) {
        $message = 'The function does not have the awaited parameters';
        throw new \ike\exception\InvalidFunction($message);
    }
    foreach ($reflex->getParameters() as $i => $param) {
        $a = $param->getName();
        $b = $input[$i];
        if ($a !== $b) {
            throw new \ike\exception\ParameterDoesNotExistsInFunction($a, $b);
        }
    }
}
