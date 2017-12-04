<?php
declare (strict_types=1);

namespace ike\util;

/**
 * Trim + downcase on a string
 */
function tokenize(string $str) : string {
  return strtolower(trim($str));
}

/**
 * Generate a token (for csrftoken, for example)
 */
function securityToken() : string {
  return bin2hex(openssl_random_pseudo_bytes(32));
}

/**
 * Check the validity of a token
 */
function checkToken(string $base, string $input) : bool {
  return hash_equals($base, $input);
}
