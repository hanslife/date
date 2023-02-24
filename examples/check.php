<?php
require __DIR__.'/../vendor/autoload.php';
use Hanslife\Date\CheckDay;

$class = new CheckDay();
$info = $class->getDate(date('Y-m-d'));
var_dump($info);
