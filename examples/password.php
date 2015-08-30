<?php

// load file
require_once '../Password.php';

// use namespace
use thom855j\PHPSecurity\Password;

/*
* Hash a password with a salt
*/
$input = 'PHP4LIFE';
$rounds = 7;
var_dump(Password::hash($input, $rounds));

/*
 * Verify that the input is indeed the hashed password
 */
$input = 'PHP4LIFE';
$data = Password::hash($input, $rounds);
var_dump(Password::verify($input, $data));