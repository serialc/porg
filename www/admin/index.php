<?php
// Filename: www/admin/index.php
// Purpose: provides administrative functionality protected by .htaccess/.htpasswd

namespace frakturmedia\porg;

use phpmailer\phpmailer;

// Composer autoloader for components
require_once("../../vendor/autoload.php");

// load PORG settings and code
// get the necessary constants, func, and class
require_once('../../php/config.php');
require_once('../../php/functions.php');
require_once('../../php/classes/mailer.php');

include '../../html/head.html';

echo <<< END
<div class="col-12">
<h2>To add</h2>
<ul>
<li>Add mailing list DE-registration</li>
<li>Restructure code, use a mailing list class</li>
</ul>
</div>
END;
include '../../php/admin.php';

include '../../php/emailing_list.php';

include '../../html/foot.html';

// EOF
