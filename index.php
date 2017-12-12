<?php
require 'ike/ike.php';

echo '<h1>Hello World</h1>';

use ike\type as type;

$ctx = new ike\Context();
$s =
  new ike\Service(
    'elete',
    'foo/bar/lol',
    [ 'foo' => type\String
    , 'bar' => type\Int
    ]
);


var_dump($s);
