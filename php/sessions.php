<?php
// Filename: php/sessions.php
// Purpose: List the markdown session files or
// display the contents of a session (convert md to html)

define ("SESSIONS_DIR", '../sessions');

echo '<div class="container">';

// If a date is provided show the session
if (count($req) > 1 ) {
    // clean date or throw error
    try {
        $sdate = new DateTime($req[1]);
        $mdf = $sdate->format('Y-m-d') . '.md';
    } catch (Exception $e) {
        echo '<h4>Session date requested is invalid</h4>';
        return;
    }

    // get the session data (in md)
    $session_md = file_get_contents('../sessions/' . $mdf);

    // convert the md to html
    $Parsedown = new Parsedown();
    echo '<i>' . $sdate->format('l, M d, Y') . '</i>';
    echo $Parsedown->text($session_md);

} else {
    // Show the dates

    // get list of files in the sessions folder
    $sess = array_values(array_diff(scandir(SESSIONS_DIR), array('.','..',    'allocated')));

    // sort so the newest date is at the top
    arsort($sess);

    echo '<h1 class="mt-5">Past PORG sessions</h1>';
    echo '<div class="row mt-3">';

    foreach($sess as $s) {
        $strdate = pathinfo($s, PATHINFO_FILENAME);
        $sdate = new DateTime($strdate);
        echo '<div class="col-md-3 col-sm-4 mb-3">';
        echo '<a href="/sessions/' . $strdate . '" class="text-decoration-none">';
        echo '<div class="h-100 py-3 px-2 bg-body-tertiary border rounded-3 text-center">';
        echo $sdate->format('l, M d, Y');
        echo '</div>';
        echo '</a>';
        echo '</div>';
    }
    echo '</div>';

}

// Close container
echo '</div>';
