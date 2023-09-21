<?php
// Filename: php/main.php
// Purpose: Main/welcome page

// read the existing configuration
$conf = json_decode(file_get_contents('config.json'), true);

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
echo $event_date->format('l, M j, Y') . ' ';
echo " (12h00-13h00)</p>";

echo "<h3>Location</h3>";
// show the location based on $conf
include('../html/locations/' . $conf['porg_location'] . '.html');

include '../html/welcome_end.html';
