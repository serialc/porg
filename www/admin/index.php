<?php
// Filename: www/admin/index.php
// Purpose: provides administrative functionality protected by .htaccess/.htpasswd

// Composer autoloader for components
require_once "../../vendor/autoload.php";

include '../../html/head.html';

echo <<< END
<h2>To add</h2>
<ul>
<li>Text area for newsletter that I can email to mailing list</li>
<li>Add mailing list DE-registration</li>
</ul>

END;

include '../../php/admin.php';

include '../../html/foot.html';

// EOF
