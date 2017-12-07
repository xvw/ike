<?php
require 'ike/ike.php';

echo '<h1>Hello World</h1>';

$ctx = new ike\Context();
$route = new ike\Route('foo/bar/{t:float}.html');
var_dump($ctx->matchWith($route));
