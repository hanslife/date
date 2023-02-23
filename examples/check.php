<?php
require __DIR__.'/../vendor/autoload.php';
use Hanslife\Date\CheckDay;

$a = 1;
var_dump((new CheckDay())->getDate());
