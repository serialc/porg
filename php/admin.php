<?php

// read the existing configuration
$conf = json_decode(file_get_contents('../config.json'), true);

// the possible locations
$porg_locs = array_diff(scandir('../../html/locations'), array('.', '..'));

// update date
if (isset($_POST['porg_date'])) {
    $conf['porg_date'] = $_POST['porg_date'];
}

// update location 
if (isset($_POST['porg_location'])) {
    $conf['porg_location'] = $_POST['porg_location'];
}

// save form based on updated $conf
if (isset($_POST['porg_date']) or isset($_POST['porg_location'])) {
    if (file_put_contents('../config.json', json_encode($conf))) {
        //print(file_get_contents('../config.json'));
        echo '<div class="alert alert-success mt-3" role="alert"><p>Update successful</p></div>';
    } else {
        echo '<div class="alert alert-danger mt-3" role="alert"><p>Failed to update</p></div>';
    }
}

// show the date form
echo '<form action="." method="post">';
echo '<div class="row">';
echo '<div class="col"><h2>Next meeting</h2>';
echo '<div class="mb-3">
     <label for="porg_date" class="form-label">Select date</label>
     <input type="date" class="form-control" autofocus="autofocus" id="porg_date" name="porg_date" maxlength="64" aria-describedby="porg_date" value="' . $conf['porg_date'] . '">
     <div class="form-text">If blank, next second Monday of month is used</div>
     </div>';
echo '</div>';

// show the location form
echo '<div class="col"><h2>Meeting location</h2>';

foreach($porg_locs as $loc) {
    $ploc = pathinfo($loc)['filename'];

    echo '<div class="form-check">';
    echo '<input class="form-check-input" type="radio"
        name="porg_location" id="' . $ploc . '" value="' . $ploc . '"';
    if ( strcmp($conf['porg_location'], $ploc) === 0 ) {
        echo " checked ";
    }
    echo '>';
    echo '<label class="form-check-label" for="' . $ploc . '">' . $ploc . '</label></div>';
}
echo '</div>';


echo '<div class="col-12">';
echo '<button type="submit" class="btn btn-primary w-100">Update</button>';
echo '</div>';

echo '</div>';
echo '</form>';

// EOF
