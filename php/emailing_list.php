<?php
// Filename: php/emailing_list.php
// Purpose: show controls for e-mail list

namespace frakturmedia\porg;

require_once('../php/classes/maillist.php');

// repackage and determine the event based on $conf and today's date
$next_event = determineNextPorgEvent($conf);

echo '<div class="container mt-2"><div class="row"><div class="col-12">';
// process the email text, send it to the mailing list
if (isset($_POST['porg_email_text'])) {

    // get the list of emailing list
    $maillist = new MailingList();

    // Create calendar invite/ICS
    $ical_content = createIcalContent($next_event['start_dt'], $next_event['end_dt'], 'PORG meeting', $conf['porg_meeting_topic'], $next_event['place']);

    // get the salf file for deregistration/unsubscription
    $sfc = file_get_contents(ADMIN_SALT_FILE);

    $sent_count = 0;

    // send the emails
    foreach( $maillist->get() as $email_address ) {
        // create hashed email
        $hashedemail = hashPassword($sfc . $email_address);
        $unsuburl = 'http://' . $_SERVER['SERVER_NAME'] . '/deregister/' . $email_address. '/' . $hashedemail;

        $email = new Mail();
        // attach ical event if requested
        // DISABLED, doesn't work as desired
        //$email->addStringAttachment($ical_content);

        // Build up the email content
        $ehtml = '<html><body>' . $_POST['porg_email_text'];

        // add deregister text at footer
        $ehtml .= '<p><a href="https://porg.digitaltwin.lu">Visit the website</a> for more information.</p>';
        $ehtml .= '<p>To unsubscribe <a href="' . $unsuburl . '">click here</a> or visit the link ' . $unsuburl . '</p></body></html>';

        if( $email->send($email_address, 'Honorable PORG', 'PORG news', $ehtml, strip_tags($ehtml)) ) {
            $sent_count += 1;
        } else {
            echo '<div class="alert alert-danger" role="alert">Failed to send message to ' . $email_address . '</div>';
        }
    }

    // show count of emails successfully sent
    echo '<div class="alert alert-success" role="alert">' . $sent_count . ' emails sent</div>';
}
echo '</div></div></div>';

// SHOW THE FORM


echo <<< END
<div class="container">
    <form action="." method="post">
        <div class="row">
            <div class="col-12 mt-2"><h1>Contact mailing list</h1></div>

            <div class="col-12">
                <textarea id="porg_email_text" name="porg_email_text" rows="10" class="w-100">
<p>Dear PORG subscriber,</p>


END;


echo '<p>The next PORG meet-up is on <strong>' . $next_event['pretty_date'] . ' at ' . $next_event['stime'] . ' - ' . $next_event['etime'] . '</strong>.<br>' . "\n";

echo '<p>The topic(s) for discussion are:<br>' . "\n";

// select Parsedown from the global namespace
$Parsedown = new \Parsedown();
echo $Parsedown->text($conf['porg_meeting_topic']);

echo "\n" . '<p>The meeting will take place here:</p>' . "\n";
if ( isset($conf['porg_location']) ) {
    include('../html/locations/' . $conf['porg_location'] . '.html');
} else {
    echo 'No location set yet' . "\n";
}

echo <<< END
                </textarea>
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary mt-3">Send emails</button>
            </div>
        </div>
    </form>
</div>
END;
