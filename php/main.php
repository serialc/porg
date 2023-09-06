<?php
// Filename: php/main.php
// Purpose: Main/welcome page

include '../html/welcome.html';

// get todays date, this month's nth Monday, next month's nth Monday
$today = new DateTime(date('Y-m-d'));
$secmon_thismonth = (clone $today)->modify('second monday of this month');
$secmon_nextmonth = (clone $today)->modify('second monday of next month');

// debug
/*
print($today->format('l, M d, Y'));
print('<br>');
print($secmon_thismonth->format('l, M d, Y'));
print('<br>');
print($secmon_nextmonth->format('l, M d, Y'));
print('<br>');
 */

// determine which Monday is the right next one (this month's or next month's)
$event_date = null;
$is_today = false;

if ($today < $secmon_thismonth) {
    $event_date = $secmon_thismonth;
} elseif ($today == $secmon_thismonth) {
    $is_today = true;
    $event_date = $secmon_thismonth;
} else {
    $event_date = $secmon_nextmonth;
}

// No event in August
// if it's August then get the following month's Monday
if ($event_date->format('m') == '08') {
    // modify the event_date
    $event_date->modify('second monday of next month');
}

// show event date
// l = day of week name
// M = month name
// d = day of month number
// Y = full year
echo $event_date->format('l, M d, Y') . ' ';

include '../html/welcome2.html';
