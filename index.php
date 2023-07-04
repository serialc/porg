<?php

include 'html/head.html';

// get todays date, this month's nth Monday, next month's nth Monday
$today = new DateTime(date('Y-m-d'));
$fmon_thismonth = (new DateTime(date('Y-m-d')))->modify('second monday of this month');
$fmon_nextmonth = (new DateTime(date('Y-m-d')))->modify('second monday of next month');

// determine which Monday is the right next one (this month's or next month's)
$event_date = null;
$is_today = false;

if ($today < $fmon_thismonth) {
    $event_date = $fmon_thismonth;
} elseif ($today == $fmon_thismonth) {
    $is_today = true;
    $event_date = $fmon_thismonth;
} else {
    $event_date = $fmon_nextmonth;
}

// No event in August
// if it's August then get the following month's Monday
if ($event_date->format('m') == '08') {
    $event_date->modify('second monday of next month');
}

// show event date
// l = day of week name
// M = month name
// d = day of month number
// Y = full year
echo $event_date->format('l M d, Y') . ' - ';

include 'html/foot.html';

?>

