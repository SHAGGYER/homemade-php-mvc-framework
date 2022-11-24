<?php
session_start();
use App\Lib\Kernel;
require_once "../bootstrap.php";

$kernel = new Kernel();
$kernel->run();