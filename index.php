<?php
require 'ike/ike.php';

echo '<h1>Hello World</h1>';

$ctx = new ike\Context();
$s = new ike\Service('get', 'foo/bar/lol', ['foo' => 'int', 'fa' => 'string']);

var_dump($s);
