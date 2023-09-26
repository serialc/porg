<?php
// Filename: php/functions.php
// Purpose: Some miscellaneous functions

function getMailingList() {
    $fh = fopen(MAILING_LIST_MEMBERS_FILENAME, 'r');
    if ($fh) {
        $maillist = fgetcsv($fh);
        return $maillist;
    }

    echo "Failed to open the mailing list file.";
    return false;
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

    $event_time = '12h00 - 13h00';
    if (strcmp($conf['porg_time'], '') !== 0) {
        echo $conf['porg_time'] . "</p>";
    }

    // format event date for humans
    // l = day of week name
    // M = month name
    // j = day of month number (without leading zero)
    // Y = full year

    return array(
        "date" => $event_date,
        "pretty_date" => $event_date->format('l, M j, Y'),
        "time" => $event_time,
        "place" => $conf['porg_location']
    );
}



// EOF