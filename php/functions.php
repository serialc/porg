<?php
// Filename: php/functions.php
// Purpose: Some miscellaneous functions

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

// EOF
