<?php
namespace ike;
session_start();

require 'util.php';
require 'Context.php';


var_dump(new Context());
var_dump(util\securityToken());
