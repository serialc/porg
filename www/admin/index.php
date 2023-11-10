<?php
// Filename: www/admin/index.php
// Purpose: provides administrative functionality protected by .htaccess/.htpasswd

namespace frakturmedia\porg;

use phpmailer\phpmailer;

ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");

// move to same directory as main page
// to use same constants
chdir('..');

// Composer autoloader for components
require_once('../vendor/autoload.php');

// load PORG settings and code
// get the necessary constants, func, and class
require_once('../conf/config.php');
require_once('../php/functions.php');
require_once('../php/classes/mailer.php');

include '../html/head.html';

include '../php/admin.php';

include '../php/emailing_list.php';

include '../html/foot.html';

// EOF
