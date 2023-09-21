<?php
// Filename: php/main.php
// Purpose: Main/welcome page

// read the existing configuration
$conf = json_decode(file_get_contents('config.json'), true);

include '../html/splash_menu.html';
include '../html/welcome.html';

// store the selected event date in this variable
$event_date = null;

// get the requested date
if (strcmp($conf['porg_date'], '') !== 0) {
    $req_date = new DateTime($conf['porg_date']);
}

// get today's date
$today = new DateTime(date('Y-m-d'));

// if the requested date is in the future, or today, display that
// need to still check if porg_date is '' as otherwise $req_date is today
if (strcmp($conf['porg_date'], '') !== 0 and ($req_date == $today or $req_date > $today)) {
    $event_date = $req_date;
} else {
    // figure out the next likely date

    # get this month's nth Monday, next month's nth Monday
    $secmon_thismonth = (clone $today)->modify('second monday of this month');
    $secmon_nextmonth = (clone $today)->modify('second monday of next month');

    // determine which Monday is the right next one (this month's or next month's)
    $is_today = false;

    if ($today < $secmon_thismonth) {
        $event_date = $secmon_thismonth;
    } elseif ($today == $secmon_thismonth) {
        $is_today = true;
        $event_date = $secmon_thismonth;
    } else {
        $event_date = $secmon_nextmonth;
    }
}

// debug
/*
print($today->format('l, M d, Y'));
print('<br>');
print($secmon_thismonth->format('l, M d, Y'));
print('<br>');
print($secmon_nextmonth->format('l, M d, Y'));
print('<br>');
 */

// show event date
// l = day of week name
// M = month name
// j = day of month number (without leading zero)
// Y = full year
echo $event_date->format('l, M j, Y') . '<br>';

if (strcmp($conf['porg_time'], '') !== 0) {
    echo $conf['porg_time'] . "</p>";
} else {
    echo "12h00 - 13h00</p>";
}

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
    $Parsedown = new Parsedown();
    echo $Parsedown->text($conf['porg_meeting_topic']);
} else {
    echo 'Free form discussion';
}
echo '</div>';

include '../html/welcome_end.html';
