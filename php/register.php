<?php
// Filename: php/register.php
// Purpose: Handles submission of email address and validation

namespace frakturmedia\porg;

require_once('../conf/config.php');
require_once('../php/functions.php');
require_once('../php/classes/mailer.php');
require_once('../php/classes/maillist.php');

echo '<div class="container">';

// salt file, used to encyrpt email, check it exists and populate if not
$sfc = file_get_contents(ADMIN_SALT_FILE);

if (!$sfc) {
    // generate a code
    $sfc = generateRandomString(20);
    // save it
    file_put_contents(ADMIN_SALT_FILE, $sfc);
}

// is an email address sent and valid
if (isset($_POST['reg_email']) and filter_var($_POST['reg_email'], FILTER_VALIDATE_EMAIL)) {
    $newmailaddress = $_POST['reg_email'];

    # check if email is already in mailing list
    # - if so and verified, just say 'added'
    # - if in but not verified, send 'regular' message regarding email confirmation
    # - if not in mailing list, add to mailing list but unverified, and send message regarding email verification

    // read registered emails
    // get the list of emailing list
    $maillist = new MailingList();

    // start building the email content
    $html = '<h1>PORG mailing list registration request</h1>';
    $text = "PORG mailing list registration request\n";
    $text .= "======================================\n";

    // check if new email is already in mailing list 
    if ($maillist->exists($newmailaddress)) {
        // Don't want to give away that it is to prevent people trying to fish out emails
        // We'll send them an email with different content but
        // we do not want to indicate anything differently on the webpage
        $html .= '<p>You are already registered for the mailing list.</p>';
        $text .= "You are already registered for the mailing list.\n";
    } else {
        // send the email with a link that contains both, their email address and the email in encrypted form
        // the link containing:
        // - The email address
        // - The encyrpted email address, using the salt
        $encryptedmail = hashPassword($sfc . $newmailaddress);
        $url = 'http://' . $_SERVER['SERVER_NAME'] . '/register/' . $newmailaddress . '/' . $encryptedmail;

        // create the email content
        $html .= '<p>Please click the <a href="' . $url . '">link</a> to confirm your registration to PORG.</p>';
        $text .= "Please visit the link (' . $url . ') to confirm your registration to PORG.\n";
    }

    $html .= '<p>If you did not make this request, ignore it or let us know.</p>';

    $text .= "If you did not make this request, ignore it or let us know.\n";

    $email = new Mail();
    if( $email->send($newmailaddress, '', 'PORG mainling list registration request', $html, $text) ) {
        echo '<h1>Email confirmation sent</h1>';
        echo '<p>An email is being sent to you to confirm your registration.</p>';
    } else {
        echo '<h1>Error</h1>';
        echo '<p>We failed to send you an email to confirm your registration.</p>';
        echo '<p>This may be our fault. If you think so, let us know.</p>';
    }
} else {
    // no valid email is submitted

    // Is there an email and a hashed salted email in the url - a response from the confirmation email?
    // something like: http://porg.digitaltwin.lu/register/cyrille@digitaltwin.lu/$2y$15......rf9uTX6
    if ( count($req) >= 3 ) {
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

            // Check that the email address is not already in the list
            // Get the mailing list
            $maillist = new MailingList();

            // if already in list
            if ($maillist->exists($this_email)) {
                echo '<h1>You are already registered</h1>';
            } else {
                $maillist->add($this_email);
                echo '<h1>Success</h1>';
                echo '<p>You are now registered to the PORG mailing list!</p>';
            }

        } else {
            echo '<h1>Error</h1>';
            echo 'Something went wrong trying to verify your email. Let us know.';
        }
    } else {
        // this is not a validation of an email address, nor is it a valid email to submit for registration
        echo '<h1>Email invalid</h1>';
        echo '<p>The email address you provided is not valid.</p>';
    }
}

echo '</div>';

// EOF
