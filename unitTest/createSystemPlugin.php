<?php
error_reporting(E_ALL);
require 'Inflector.php';

var_dump(getenv('PLUGIN_NAME'));
var_dump(getenv('AUTHOR_NAME'));
var_dump(getenv('AUTHOR_EMAIL'));
var_dump(Inflector::underscore(getenv('PLUGIN_NAME')));
var_dump(Inflector::singularize(getenv('PLUGIN_NAME')));

