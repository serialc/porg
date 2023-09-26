<?php
// Filename: php/admin.php
// Purpose: show controls next porg meet, location, and topic

// read the existing configuration - if it doesn't exist, it's fine
$conf = json_decode(file_get_contents('../config.json'), true);

//var_dump($_POST);

// the possible locations
$porg_locs = array_diff(scandir('../../html/locations'), array('.', '..'));

// update date
if (isset($_POST['porg_date'])) {
    $conf['porg_date'] = $_POST['porg_date'];
}

// update time
if (isset($_POST['porg_time'])) {
    $conf['porg_time'] = $_POST['porg_time'];
}

// update location 
if (isset($_POST['porg_location'])) {
    $conf['porg_location'] = $_POST['porg_location'];
}

// update topic 
if (isset($_POST['porg_meeting_topic'])) {
    $conf['porg_meeting_topic'] = $_POST['porg_meeting_topic'];
}

// save form based on updated $conf
if (isset($_POST['porg_date']) or isset($_POST['porg_location']) or isset($_POST['porg_time'])) {
    if (file_put_contents('../config.json', json_encode($conf))) {
        //print(file_get_contents('../config.json'));
        echo '<div class="alert alert-success mt-3" role="alert"><p>Update successful</p></div>';
    } else {
        echo '<div class="alert alert-danger mt-3" role="alert"><p>Failed to update</p></div>';
    }
}

// show the date form
echo '<div class="container">';
echo '<form action="." method="post">';
echo '<div class="row">';
echo '<div class="col-12"><h1>Next event details</h1></div>';

echo '<div class="col-lg-4 col-md-6"><h2>Next meeting date-time</h2>';
echo '<div class="input-group">
     <label for="porg_date" class="input-group-text">Select date</label>
     <input type="date" class="form-control" id="porg_date" name="porg_date" value="' . $conf['porg_date'] . '">
     </div>';
     echo '<div class="form-text mb-2">If blank, next second Monday of month is used</div>';
echo '<div class="input-group">
     <label for="porg_time" class="input-group-text">Enter time</label>
     <input type="text" class="form-control" id="porg_time" name="porg_time" value="' . $conf['porg_time'] . '">
     </div>';
     echo '<div class="form-text mb-2">If blank, default time of 12h00 - 13h00 is used</div>';
echo '</div>';

// show the location form
echo '<div class="col-lg-4 col-md-6"><h2>Meeting location</h2>';

foreach($porg_locs as $loc) {
    $ploc = pathinfo($loc)['filename'];

    echo '<div class="btn-group-vertical" role="group">';
    echo '<input class="btn-check" type="radio"
        name="porg_location" id="' . $ploc . '" value="' . $ploc . '"';
    if ( strcmp($conf['porg_location'], $ploc) === 0 ) {
        echo " checked ";
    }
    echo '>';
    echo '<label class="btn btn-outline-primary" for="' . $ploc . '">' . $ploc . '</label></div>';
}
echo '</div>';

// Next topic
echo '<div class="col-lg-4 col-md-6"><h2>Next meeting topic</h2>';
echo '<textarea id="porg_meeting_topic" name="porg_meeting_topic" rows="5" class="w-100">' .
    $conf['porg_meeting_topic'] .
    '</textarea>';
echo '<div class="form-text mb-2">The above is markdown that will be parsed to HTML</div>';
echo '</div>';

echo '<div class="col-12">';
echo '<button type="submit" class="btn btn-primary w-100 mt-3">Update</button>';
echo '</div>';

echo '</div>';
echo '</form>';
echo '</div>';

// EOF
