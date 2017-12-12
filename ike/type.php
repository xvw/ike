<?php
declare(strict_types=1);

namespace ike\type;

/**
 * Define the types for HTTP parameters
 */
const String = 0;
const Free = 0;
const Untyped = 0;
const Text = 0;
const Integer = 1;
const Int = 1;
const Float = 2;
const Char = 3;
const Character = 3;
const Bool = 4;
const Boolean = 4;
const File = 5;

/**
 * Check if a type is valid for a service
 * @param string $method the HTTP method
 * @param string $key the type name
 * @param int $type the type value
 * @throws \ike\exception\InvalidHTTPType
 */
function validHTTPType(string $method, string $key, int $type)
{
    if ($type < String || $type > File) {
        $message = '['.$key.'] has an unknown type';
        throw new \ike\exception\InvalidHTTPType($message);
    }
    if ($type === File && $method != 'post') {
        $message = '['.$key.'] has type [file], ';
        $message .= 'this type is only allowed for POST service';
        throw new \ike\exception\InvalidHTTPType($message);
    }
}

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
