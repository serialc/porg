<?php
// Filename: php/unsubscribe.php
// Purpose: Handles submission of email address and hashed validation for deregsitration/unsubscribing to mailing list

namespace frakturmedia\porg;

require_once('../conf/config.php');
require_once('../php/functions.php');
require_once('../php/classes/mailer.php');
require_once('../php/classes/maillist.php');

echo '<div class="container"><div class="row"><div class="col">';

// salt file, used to encyrpt email, check it exists and populate if not
$sfc = file_get_contents(ADMIN_SALT_FILE);

$this_email = $req[1];
$salted_email = $sfc . $this_email;

// so there's no error if someone tests the URL
if ( count($req) < 3 ) {
    echo '<h1>Invalid request</h1>';
    echo '<p>The form of your request is unexpected. Reach out to us to let us know.</p>';
    return;
}

// there may be a slash in the hashed email
// need to select $req[2+]
$hashed_email = $req[2];
if ( count($req) > 3 ) {
    $hashed_email = implode('/', array_splice($req, 2));
}

// need to decode URLs with special characters
$hashed_email = urldecode($hashed_email);

// check that passed password is valid and that the salted email is correct
if ( strcmp(filter_var($this_email, FILTER_VALIDATE_EMAIL), $this_email) === 0
    and password_verify($salted_email, $hashed_email) ) {
    // this is a successful validation
    // (this person has permission)
    
    // get the emailing list
    $maillist = new MailingList();

    // if already in list
    if ($maillist->exists($this_email)) {
        $maillist->remove($this_email);

        echo '<h1>You have been unsubscribed</h1>';
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
