<?php
// Filename: php/emailing_list.php
// Purpose: show controls for e-mail list

namespace frakturmedia\porg;

require_once('../php/classes/maillist.php');

// repackage and determine the event based on $conf and today's date
$next_event = determineNextPorgEvent($conf);
// get the list of emailing list
$maillist = new MailingList();

// open the form
echo '<div class="container mt-2">
    <form action="." method="post">
        <div class="row">
            <div class="col-12 mt-2"><h1>Contact mailing list</h1></div>';


// process the email text, send it to the mailing list
if (isset($_POST['porg_email_text'])) {
    echo '<div class="row"><div class="col-12">';

    $send_calendar_invite = false;
    if ( strcmp('on', $_POST['send_calendar_invite']) === 0 ) {
        $send_calendar_invite = true;
    }

    // get the salf file for deregistration/unsubscription
    $sfc = file_get_contents(ADMIN_SALT_FILE);

    $sent_count = 0;

    // send the emails
    foreach( $maillist->get() as $email_address ) {
        // create hashed email
        $hashedemail = hashPassword($sfc . $email_address);
        $unsuburl = 'http://' . $_SERVER['SERVER_NAME'] . '/deregister/' . $email_address. '/' . $hashedemail;

        $email = new Mail();

        // if sending of calendar invitation is enabled
        if ( $send_calendar_invite ) {
            $event = new Mail();

            // Create calendar invite/ICS
            $ical_content = createIcalContent($next_event['start_dt'], $next_event['end_dt'], 'PORG meeting', $conf['porg_meeting_topic'], $next_event['place'], $email_address);

            // attach ical event if requested
            $event->addStringAttachment($ical_content);
            $event->send($email_address, 'Honorable PORG', 'PORG news', '<b>Event HTML</b>', 'Event Text');
        }

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

    // close the col and row
    echo '</div></div>';
}

echo '<div class="col-12 my-2"><p class="text-primary mb-1">Members on the mailing list: <b>' . $maillist->count() . '</b></p></div>';

echo <<< END
            <div class="col-12">
                <textarea id="porg_email_text" name="porg_email_text" rows="10" class="w-100">
<p>Dear PORG subscriber,</p>
END;

// Create the template text for the email to send out
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
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="send_calendar_invite" name="send_calendar_invite">
                    <label class="form-check-label" for="send_calendar_invite">Also send calendar invitation</label>
                </div>
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary mt-3">Send emails</button>
            </div>
        </div>
    </form>
</div>
END;
