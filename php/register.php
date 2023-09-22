<?php
// Filename: php/register.php
// Purpose: Handles submission of email address and validation

namespace frakturmedia\porg;

require_once('../php/config.php');
require_once('../php/functions.php');
require_once '../php/classes/mailer.php';

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
$newmailaddress = $_POST['reg_email'];
if (isset($newmailaddress) and filter_var($newmailaddress, FILTER_VALIDATE_EMAIL)) {

    # check if email is already in mailing list
    # - if so and verified, just say 'added'
    # - if in but not verified, send 'regular' message regarding email confirmation
    # - if not in mailing list, add to mailing list but unverified, and send message regarding email verification

    // the mailing list file
    $mlfn = 'admin/mailing_list.csv';

    // check it exists, create it if not
    if (!file_exists($mlfn)) {
        touch($mlfn);
    }

    // read registered emails
    $fh = fopen('admin/mailing_list.csv','r');
    $maillist = fgetcsv($fh);

    // start building the email content
    $html = '<h1>PORG mailing list registration request</h1>';
    $text = "PORG mailing list registration request\n";
    $text .= "======================================\n";

    // check if new email is already in mailing list 
    if ($maillist and in_array($newmailaddress, $maillist)) {
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
    $html .= '<p>Ability to deregister will be added soon.</p>';

    $text .= "If you did not make this request, ignore it or let us know.\n";
    $text .= "Ability to deregister will be added soon.\n";

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
    if ( count($req) === 3 ) {
        $salted_email = $sfc . $req[1];

        // check that password is passed in req[1] and that the salted email is valid in password_verify
        if ( filter_var($req[1], FILTER_VALIDATE_EMAIL) and password_verify($salted_email, $req[2]) ) {
            // this is a successful validation
            // add email address to mailing list
            $fh = fopen('admin/mailing_list.csv','a');
            fwrite($fh, $req[1] . ',');

            echo '<h1>Success</h1>';
            echo '<p>You are now registered to the PORG mailing list!</p>';
        } else {
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
