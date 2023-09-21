<?php

// Composer autoloader for components
require_once "../vendor/autoload.php";

$req = explode("/", ltrim($_SERVER['REQUEST_URI'], "/"));
$page = $req[0];

include '../html/head.html';

switch ($page) {

case 'sessions':
    include '../php/sessions.php';
    break;

default:
    include '../php/main.php';
}

include '../html/foot.html';

// EOF

