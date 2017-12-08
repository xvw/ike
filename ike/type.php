<?php
declare(strict_types=1);

namespace ike\type;

/**
 * Defines the list of all "regexable types"
 * @return array
 */
function regexableTypes() : array
{
    return [
    'string' => '.*'
  , 'int'    => '[\+\-]?\d+'
  , 'float'  => '[\+\-]?\d+\.\d*'
  , 'char'   => '.'
  , 'bool'   => 'true|false'
  ];
}

/**
 * Gives a regex for a specific type
 * @param string $type the gived type
 * @return string
 * @throws exception\InvalidType
 */
function regexFor(string $type) : string
{
    $token = \ike\util\tokenize($type);
    $types = regexableTypes();
    if (\array_key_exists($token, $types)) {
        return $types[$token];
    }
    $suggestions = \array_keys($types);
    $suggestion = \ike\util\suggestionFor($token, $suggestions);
    throw new \ike\exception\InvalidType($token, $suggestion);
}
