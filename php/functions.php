<?php
// Filename: php/functions.php
// Purpose: Some miscellaneous functions

function createIcalContent ($start, $end, $name, $description, $location ) {
    $ical_content = 'BEGIN:VCALENDAR
PRODID:-//Mailer//NONSGML v1.0//EN
VERSION:2.0
CALCSCALE:GREGORIAN
METHOD:REQUEST
BEGIN:VEVENT
DTSTART:' . date("Ymd\THis\Z", strtotime($start . 'CET')) . '
DTEND:' . date("Ymd\THis\Z", strtotime($end . 'CET')) . '
ORGANIZER;CN=PORG:mailto:' . EMAIL_REPLYTO . '
UID:' . md5(uniqid(mt_rand(), true)) . '@digitaltwin.lu
CREATED:' . gmdate('Ymd').'T'. gmdate('His') . 'Z
DESCRIPTION:Productivity Open Research Group monthly meeting
LAST-MODIFIED:' . gmdate('Ymd').'T'. gmdate('His') . 'Z
SUMMARY:
LOCATION:Belval, Luxembourg
END:VEVENT
END:VCALENDAR
';
    return $ical_content;
}

// https://stackoverflow.com/questions/4356289/php-random-string-generator
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

function hashPassword($pw)
{
    return password_hash(
        $pw,
        PASSWORD_DEFAULT,
        ['cost' => PASSWORD_HASH_COST]
    );
}

function determineNextPorgEvent($conf)
{
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

    // Get the time, or use the default if not set
    $stime = '12:00';
    $etime = '13:00';

    if (strcmp($conf['porg_stime'], '') !== 0) {
        $stime = $conf['porg_stime'];
    }
    if (strcmp($conf['porg_etime'], '') !== 0) {
        $etime = $conf['porg_etime'];
    }

    // format event date for humans
    // l = day of week name
    // M = month name
    // j = day of month number (without leading zero)
    // Y = full year

    return array(
        "date" => $event_date,
        "pretty_date" => $event_date->format('l, M j, Y'),
        "stime" => $stime,
        "etime" => $etime,
        "place" => $conf['porg_location'],
        "start_dt" => $event_date->format('Y-m-d') . ' ' . $stime,
        "end_dt" => $event_date->format('Y-m-d') . ' ' . $etime,
    );
}


// EOF
