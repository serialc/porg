<?php
// File: php/classes/icsgenerator.php
// Purpose: Ease the creation of calendar event (ics)

namespace frakturmedia\porg;

require_once('../php/config.php');

// https://blog.trixpark.com/how-to-create-ics-icalendar-file/
class ICSGenerator {

    private $data;
    private $name;

    function __construct( $start, $end, $name, $description, $location ) {
        $this->name = $name;
        $this->data = "BEGIN:VCALENDAR\nVERSION:2.0\nMETHOD:PUBLISH\nBEGIN:VEVENT\n" .
            'DTSTART:' . date("Ymd\THis\Z",strtotime($start)) . "\n" .
            'DTEND:' . date("Ymd\THis\Z",strtotime($end)) . "\n" .
            'ORGANIZER;CN=' . EMAIL_REPLYTO . ':mailto:' . EMAIL_REPLYTO . "\n" . 
            'LOCATION:' . $location . "\nTRANSP: OPAQUE\nSEQUENCE:0\nUID:\n" .
            'DTSTAMP:' . date("Ymd\THis\Z") . "\nSUMMARY:" . $name . "\n" .
            'DESCRIPTION:' . $description . "\nPRIORITY:5\nCLASS:PUBLIC\n" .
            "BEGIN:VALARM\nTRIGGER:-PT60M\nACTION:DISPLAY\nDESCRIPTION:PORG reminder\nEND:VALARM\nEND:VEVENT\nEND:VCALENDAR\n";
    }

    public function save() {
        //file_put_contents("saved-ics/".$this->name.".ics",$this->data);
    }
}

// EOF
