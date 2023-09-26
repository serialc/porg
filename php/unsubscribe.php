<?php
// Filename: php/unsubscribe.php
// Purpose: Handles submission of email address and hashed validation for deregsitration/unsubscribing to mailing list

namespace frakturmedia\porg;

require_once('../php/config.php');
require_once('../php/functions.php');
require_once('../php/classes/mailer.php');

echo '<div class="container"><div class="row"><div class="col">';

// salt file, used to encyrpt email, check it exists and populate if not
$sfc = file_get_contents(ADMIN_SALT_FILE);

$this_email = $req[1];
$salted_email = $sfc . $this_email;

$hashed_email = $req[2];

// there may be a slash in the hashed email
// need to select $req[2+]
if ( count($req) > 3 ) {
    $hashed_email = implode('/', array_splice($req, 2));
}

// check that passed password is valid and that the salted email is correct
if ( strcmp(filter_var($this_email, FILTER_VALIDATE_EMAIL), $this_email) === 0
    and password_verify($salted_email, $hashed_email) ) {
    // this is a successful validation
    // (this person has permission)
    
    // get the emailing list
    $maillist = getMailingList();
    if ($ml === false) {
        // message already sent by function
        return;
    }

    // if already in list
    if (in_array($this_email, $maillist)) {
        // remove them
        $email_index = array_search($this_email, $maillist);
        unset($maillist[$email_index]);

        // save updated email list
        file_put_contents(MAILING_LIST_MEMBERS_FILENAME, implode(',', $maillist) . ',');

        echo '<h1>Unsubscribe successful</h1>';
        echo '<p>So long, and thanks for all the fish!</p>';
        echo '<p><img src="/imgs/porg.svg"></p>';
    } else {
        echo '<h1>Email address not found</h1>';
        echo '<p>Perhaps you have already completed the action?</p>';
    }
} else {
    // this is not a validation of an email address, nor is it a valid email to submit for registration
    echo '<h1>Email invalid</h1>';
    echo '<p>The email address you provided is not valid.</p>';
}

echo '</div></div></div>';

// EOF
