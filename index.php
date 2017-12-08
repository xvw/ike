<?php
require 'ike/ike.php';

echo '<h1>Hello World</h1>';

$ctx = new ike\Context();
$route = new ike\Route('*');
$f = function ($a, $b, $c) {
};

var_dump($route->link(['t' => 1]));
