<?php
// Filename: config_template.php
// Purpose: variables that are more general and not sensitive

namespace frakturmedia\porg;

// =============== START of define in php/config.php =================
// Define email parameters
// define('EMAIL_HOST', 'smtp.example.com');
// define('EMAIL_SENDER', 'user@example.com');
// define('EMAIL_REPLYTO', 'info@example.com');
// define('EMAIL_REPLYTONAME', 'Mailer name');
// define('EMAIL_PASSWORD', 'password');
// define('EMAIL_PORT', 465);
// =============== END of define in php/config.php =================

// other config
define("PASSWORD_HASH_COST", 10);
define("ADMIN_SALT_FILE", "admin/salt_file.txt");
define("MAILING_LIST_MEMBERS_FILENAME", "admin/mailing_list.csv");

