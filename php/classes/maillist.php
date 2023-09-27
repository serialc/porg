<?php
// Filename: php/classes/maillist.php
// Purpose: Handles load and manipulations of mailing list

namespace frakturmedia\porg;

require_once('../php/config.php');

class MailingList {

    private $list;

    function __construct( )
    {
        // check if it exists, create it if not
        if (!file_exists(MAILING_LIST_MEMBERS_FILENAME)) {
            touch(MAILING_LIST_MEMBERS_FILENAME);
        }

        // retrieve the data and trim any unexpected spaces or commas
        $raw_file = trim(file_get_contents(MAILING_LIST_MEMBERS_FILENAME), ', ');

        // if retrieved correctly
        if ($raw_file !== false) {
            // if empty, create empty array
            if ( strcmp($raw_file, '') === 0) {
                $this->list= [];
            } else {
                // transform from csv to list and remove any trailing spaces, commas
                $this->list = explode(',', $raw_file);
            }
        } else {
            echo "Failed to open the mailing list file.";
            return false;
        }
    }

    private function save ()
    {
        file_put_contents(MAILING_LIST_MEMBERS_FILENAME, implode(',', $this->list));
    }

    function remove ($email)
    {
        $index = array_search($email, $this->list);
        unset($this->list[$index]);
        $this->save();
    }

    function add ($email)
    {
        array_push($this->list, $email);
        $this->save();
    }

    function exists ($email)
    {
        if ( in_array($email, $this->list) ) {
            return true;
        }
        return false;
    }

    function get ()
    {
        return $this->list;
    }
}
