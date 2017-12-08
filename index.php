<?php
require 'ike/ike.php';

echo '<h1>Hello World</h1>';

$ctx = new ike\Context();
$route = new ike\Route('foo/bar/{t:float}.html');
$f = function ($a, $b, $c) {
};

var_dump(ike\util\isValidFunctionParameters($f, ['a', 'b', 'c']));
