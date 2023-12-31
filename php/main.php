<?php
// Filename: php/main.php
// Purpose: Main/welcome page

namespace frakturmedia\porg;

require_once('../conf/config.php');
require_once('../php/functions.php');

// read the existing configuration
$conf = json_decode(file_get_contents(EVENT_DETAILS_FILE), true);

include '../html/splash_menu.html';
include '../html/welcome.html';

// repackage and determine the event based on $conf and today's date
$next_event = determineNextPorgEvent($conf);

echo $next_event['pretty_date'] . '<br>' . $next_event['stime'] . ' - ' . $next_event['etime'] . '</p>';

// select Parsedown from the global namespace
$parsedown = new \Parsedown();

echo "<h3>Location</h3>";
// show the location based on $conf
if ( isset($conf['porg_location']) ) {
    $room = json_decode(file_get_contents(EVENT_ROOMS_FOLDER . $conf['porg_location']), true);
    echo $parsedown->text($room['description']);
} else {
    echo 'No location set yet';
}

// show the suggested next meeting topics
echo '<h3 class="mt-5">Suggested topic for next meeting</h3>';
echo '<div id="next_meeting_topic">';
if ( $conf['porg_meeting_topic'] ) {

    echo $parsedown->text($conf['porg_meeting_topic']);

} else {

    echo 'Free form discussion';

}
echo '</div>';

include '../html/welcome_end.html';
