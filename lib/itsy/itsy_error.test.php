<?php

define(ITSY_PATH, true);
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', TRUE);

require 'itsy_error.class.php';

$e = new itsy_error();

$e->add('foo', 'message 1');
$e->add('foo', 'message 2');
$e->add('foo', 'message 3');

$e->add('bar', 'message 4');


//var_dump($e->count());

var_dump($e->on('foo'));

//var_dump($e->clear());
//var_dump($e->count());

//var_dump($e->on('foo'));

?>