<?php
// Composer autoloader for components
require_once "../../vendor/autoload.php";

include '../../html/head.html';

echo <<< END
<h2>To add</h2>
<ul>
<li>Select time</li>
<li>Update front page with links to larger sections below</li>
<li>Add a 'next meeting topic' to page</li>
<li>Email textarea to mailing list</li>
<li>If no conf file exists, create one</li>
<li>Add mailing list registration</li>
</ul>

END;

include '../../php/admin.php';

include '../../html/foot.html';

// EOF
