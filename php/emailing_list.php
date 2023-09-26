<?php
// Filename: php/emailing_list.php
// Purpose: show controls for e-mail list

namespace frakturmedia\porg;

echo '<div class="container mt-5"><div class="row"><div class="col-12">';
// process the email text, send it to the mailing list
if (isset($_POST['porg_email_text'])) {

    // get the list of emailing list
    $fh = fopen('../' . MAILING_LIST_MEMBERS_FILENAME, 'r');
    if ($fh) {
        $maillist = fgetcsv($fh);
    } else {
        echo "Failed to open the mailing list file.";
    }

    $sent_count = 0;

    // send the emails
    foreach( $maillist as $email_address ) {
        // last one is empty
        if( $email_address === '' ) {
            continue;
        }

        $email = new Mail();
        $etxt = $_POST['porg_email_text'];
        $ehtml = '<html><body>' . $_POST['porg_email_text'];

        // add deregister text at footer
        $ehtml .= '<p><a href="https://porg.digitaltwin.lu">Visit the website</a> for more information.</p>';
        $ehtml .= '<p>To unsubscribe ... coming soon</p></body></html>';

        if( $email->send($email_address, '', 'PORG news', $ehtml, $etxt) ) {
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

// repackage and determine the event based on $conf and today's date
$next_event = determineNextPorgEvent($conf);

echo <<< END
<div class="container">
    <form action="." method="post">
        <div class="row">
            <div class="col-12 mt-4"><h1>Message mailing list</h1></div>

            <div class="col-12">
                <textarea id="porg_email_text" name="porg_email_text" rows="10" class="w-100">
<p>Dear PORG subscriber,</p>


END;

// Need to add calendar invite/ ICS
// See:
// https://blog.trixpark.com/how-to-create-ics-icalendar-file/

echo '<p>The next PORG meet-up is on <strong>' . $next_event['pretty_date'] . ' at ' . $next_event['time'] . '</strong>.<br>' . "\n";

echo '<p>The topic(s) for discussion are:<br>' . "\n";

// select Parsedown from the global namespace
$Parsedown = new \Parsedown();
echo $Parsedown->text($conf['porg_meeting_topic']);

echo "\n" . '<p>The meeting will take place here:</p>' . "\n";
if ( isset($conf['porg_location']) ) {
    include('../../html/locations/' . $conf['porg_location'] . '.html');
} else {
    echo 'No location set yet' . "\n";
}

echo <<< END
                </textarea>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary w-100 mt-3">Send emails</button>
            </div>
        </div>
    </form>
</div>
END;
