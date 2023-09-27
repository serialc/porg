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
require_once('../php/config.php');
require_once('../php/functions.php');
require_once('../php/classes/mailer.php');

include '../html/head.html';

echo <<< END
<div class="container">
<div class="row">
<div class="col-12">
<h2>To add</h2>
<ul>
<li>Add checkbox to enable calendar invites with next event</li>
<li>Attach calendar invite with mailing events. Didn't manage to get it recognized as anything but an attachment.</li>
</ul>
</div>
</div>
</div>
END;

include '../php/admin.php';

include '../php/emailing_list.php';

include '../html/foot.html';

// EOF
