<?php
declare(strict_types=1);

namespace ike\type;

/**
 * Define the types for HTTP parameters
 */
const Free = 0;
const Untyped = 0;
const String = 1;
const Text = 1;
const Integer = 2;
const Int = 2;
const Float = 3;
const Char = 4;
const Character = 4;
const Bool = 5;
const Boolean = 5;
const File = 6;

/**
 * Check if a type is valid for a service
 * @param string $method the HTTP method
 * @param string $key the type name
 * @param mixed $type the type value
 * @throws \ike\exception\InvalidHTTPType
 */
function validHTTPType(string $method, string $key, $type)
{
    if (\is_array($type)) {
        if (count($type) > 1) {
            $message = '['.$key.'] has an unknown type';
            throw new \ike\exception\InvalidHTTPType($message);
        }
        return validHTTPType($method, $key, $type[0]);
    }
    if ($type < Free || $type > File) {
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
