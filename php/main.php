<?php
// Filename: php/main.php
// Purpose: Main/welcome page

namespace frakturmedia\porg;

require_once('../php/functions.php');

// read the existing configuration
$conf = json_decode(EVENT_DETAILS_FILE), true);

include '../html/splash_menu.html';
include '../html/welcome.html';

// repackage and determine the event based on $conf and today's date
$next_event = determineNextPorgEvent($conf);

echo $next_event['pretty_date'] . '<br>' . $next_event['time'] . '</p>';

echo "<h3>Location</h3>";
// show the location based on $conf
if ( isset($conf['porg_location']) ) {
    include('../html/locations/' . $conf['porg_location'] . '.html');
} else {
    echo 'No location set yet';
}

// show the suggested next meeting topics
echo '<h3 class="mt-5">Suggested topic for next meeting</h3>';
echo '<div id="next_meeting_topic">';
if ( $conf['porg_meeting_topic'] ) {

    // select Parsedown from the global namespace
    $Parsedown = new \Parsedown();
    echo $Parsedown->text($conf['porg_meeting_topic']);

} else {

    echo 'Free form discussion';

}
echo '</div>';

include '../html/welcome_end.html';
