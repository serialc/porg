<?php
// Filename: index.php
// Purpose: Main landing file of PORG

namespace frakturmedia\porg;

ini_set("log_errors", 1);
ini_set("error_log", "../php-error.log");

// Composer autoloader for components
require_once("../vendor/autoload.php");

$req = explode("/", ltrim($_SERVER['REQUEST_URI'], "/"));
$page = $req[0];

include '../html/head.html';

switch ($page) {

case 'deregister':
    include '../php/unsubscribe.php';
    break;

case 'register':
    include '../php/register.php';
    break;

case 'sessions':
    include '../php/sessions.php';
    break;

default:
    include '../php/main.php';
}

include '../html/foot.html';

// EOF

