<?php
// Filename: php/classes/maillist.php
// Purpose: Handles load and manipulations of mailing list

namespace frakturmedia\porg;

require_once('../conf/config.php');

class MailingList {

    private $list;
    private $raw;

    function __construct( )
    {
        // check if it exists, create it if not
        if (!file_exists(MAILING_LIST_MEMBERS_FILENAME)) {
            touch(MAILING_LIST_MEMBERS_FILENAME);
        }

        // retrieve the data and trim any unexpected spaces or commas
        $this->raw = trim(file_get_contents(MAILING_LIST_MEMBERS_FILENAME), ', ');

        // if retrieved correctly
        if ($this->raw !== false) {
            // if empty, create empty array
            if ( strcmp($this->raw, '') === 0) {
                $this->list= [];
            } else {
                // transform from csv to list and remove any trailing spaces, commas
                $this->list = explode(',', $this->raw);
            }
        } else {
            echo "Failed to open the mailing list file.";
            return false;
        }
    }

    public function count ()
    {
        return count($this->list);
    }

    private function save ()
    {
        if (!file_exists(MAILING_ARCHIVE_FOLDER)) {
            mkdir(MAILING_ARCHIVE_FOLDER);
        }

        # backup list
        file_put_contents(MAILING_ARCHIVE_FOLDER . 'ML_' . date('Y-m-d_H-i-s') . '.csv', $this->raw);

        # overwrite 
        file_put_contents(MAILING_LIST_MEMBERS_FILENAME, implode(',', $this->list));
    }

    public function remove ($email)
    {
        $index = array_search($email, $this->list);
        unset($this->list[$index]);
        $this->save();
    }

    public function add ($email)
    {
        array_push($this->list, $email);
        $this->save();
    }

    public function exists ($email)
    {
        if ( in_array($email, $this->list) ) {
            return true;
        }
        return false;
    }

    public function get ()
    {
        return $this->list;
    }
}
