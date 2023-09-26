<?php
// Filename: www/admin/index.php
// Purpose: provides administrative functionality protected by .htaccess/.htpasswd

namespace frakturmedia\porg;

use phpmailer\phpmailer;

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
<div class="col-12">
<h2>To add</h2>
<ul>
<li>Restructure code, use a mailing-list class</li>
<li>Add calendar invite with mailing events</li>
</ul>
</div>
END;

include '../php/admin.php';

include '../php/emailing_list.php';

include '../html/foot.html';

// EOF
