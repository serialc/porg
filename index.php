<?php

include 'html/head.html';

$today = new DateTime(date('Y-m-d'));
$fmon_thismonth = (new DateTime(date('Y-m-d')))->modify('first monday of this month');
$fmon_nextmonth = (new DateTime(date('Y-m-d')))->modify('first monday of next month');

// testing
//echo $today->format('Y-m-d') . '<br>';
//echo $fmon_thismonth->format('Y-m-d') . '<br>';
//echo $fmon_nextmonth->format('Y-m-d') . '<br>';

if ($today < $fmon_thismonth) {
    echo $fmon_thismonth->format('Y-m-d') . ' - ';
} elseif ($today == $fmon_thismonth) {
    echo 'Today (' . $fmon_thismonth->format('Y-m-d') . ') - ';
} else {
    echo $fmon_nextmonth->format('Y-m-d') . ' - ';
}

include 'html/foot.html';

?>

