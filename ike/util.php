<?php
declare (strict_types=1);

namespace ike\util;

/**
 * Trim + downcase on a string
 * @param string $str the string to be tokenized
 * @return string
 */
function tokenize(string $str) : string {
  return strtolower(trim($str));
}

/**
 * Generate a token (for csrftoken, for example)
 * @return string
 */
function securityToken() : string {
  return bin2hex(openssl_random_pseudo_bytes(32));
}

/**
 * Check the validity of a token
 * @param string $base the hash used as a reference
 * @param string $input the gived hash
 * @return bool
 */
function checkToken(string $base, string $input) : bool {
  return hash_equals($base, $input);
}

/**
 * Perform a  suggestion for a data based on a container
 * @param string $key the data
 * @param array keys to be matched
 * @return string
 */
function suggestionFor(string $key, array $keys) : string {
  $len = count($keys);
  if ($len == 0 || strlen($key) < 1) return 'nothing';
  if ($len == 1) return $keys[0];
  $clone = $keys;
  (usort($clone, function($keyA, $keyB) use ($key) {
    $a = levenshtein($key, $keyA);
    $b = levenshtein($key, $keyB);
    return ($a-$b) ? ($a-$b)/abs($a-$b) : 0;
  }));
  return $clone[0];
}
