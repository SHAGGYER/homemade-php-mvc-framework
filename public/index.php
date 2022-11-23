<?php
session_start();
use App\Lib\Kernel;

require_once "../app/bootstrap.php";

$kernel = new Kernel();
$kernel->run();