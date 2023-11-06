<?php
// Filename: php/functions.php
// Purpose: Some miscellaneous functions

namespace frakturmedia\porg;

use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;

use Eluceo\iCal\Domain\ValueObject\Timestamp;

use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\DateTime;

use Eluceo\iCal\Domain\ValueObject\Uri;

use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\GeographicPosition;

use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Domain\ValueObject\EmailAddress;

use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;

function createIcalContent ($start, $end, $name, $description, $location, $dest_email ) {

    // create the event with a unique identifier
    $event = (new Event( new UniqueIdentifier('porg.digitaltwin.lu')))
        ->touch(new Timestamp())
        ->setSummary($name)
        ->setDescription($description)
        ->setOccurrence(
            new TimeSpan(
                new DateTime(\DateTimeImmutable::createFromFormat('Y-m-d H:i', $start), false),
                new DateTime(\DateTimeImmutable::createFromFormat('Y-m-d H:i', $end), false)
            )
        )
        ->setUrl( new Uri('https://' . $_SERVER['SERVER_NAME']))
        ->setLocation( 
            (new Location($location))
                ->withGeographicPosition(new GeographicPosition(49.504558,5.9464613))
        )
        ->setOrganizer(
            new Organizer(
                new EmailAddress(EMAIL_REPLYTO),
                'PORG'
            )
        );

    $calendar = new Calendar([$event]);
    $ical_content = (new CalendarFactory())->createCalendar($calendar);
    return (string) $ical_content;
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
        $req_date = new \DateTime($conf['porg_date']);
    }

    // get today's date
    $today = new \DateTime(date('Y-m-d'));

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
