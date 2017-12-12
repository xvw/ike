<?php
require 'ike/ike.php';

echo '<h1>Hello World</h1>';

use ike\type as type;

$ctx = new ike\Context();
$s =
  new ike\Service(
    'post',
    'foo/bar/lol',
    [ 'foo' => [type\String]
    , 'bar' => type\File
    ]
);


var_dump($s->isBootable($ctx));
