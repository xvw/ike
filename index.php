<?php
require 'ike/ike.php';

echo '<h1>Hello World</h1>';


var_dump(new ike\Application());
var_dump(ike\Route::get('foo/bar/{t:float}.html'));
