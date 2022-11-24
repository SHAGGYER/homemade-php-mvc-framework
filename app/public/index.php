<?php
session_start();
use App\Lib\Kernel;
use Dotenv\Dotenv;

require_once "../bootstrap.php";

$dotenv = Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

$kernel = new Kernel();
$kernel->run();