<?php
// Filename: php/classes/maillist.php
// Purpose: Handles load and manipulations of mailing list

namespace frakturmedia\porg;

require_once('../php/config.php');

class MailingList {

    private $list;

    function __construct( )
    {
        $list = file_get_contents(MAILING_LIST_MEMBERS_FILENAME);
        if ($list) {
            $list = $list.rstrip(',');
            $list = explode(',', $list);
        } else {
            echo "Failed to open the mailing list file.";
        }
    }

    function getList()
    {
        return $list;
    }
}
