<?php

// load file
require_once '../src/Hash.php';

// use namespace
use Datalaere\PHPSecurity\Hash;

/*
* Create a random hash string
*/
$string = '234KR3?+3';
$salt = 7;
var_dump(Hash::create($string, $salt));

/*
 * Create a random string
 */
$length = 64;
var_dump(Hash::rand($length));

/*
 * Create a nu unique hash
 */
$salt = 7;
var_dump(Hash::unique($salt));

/*
 * Encrypt a string input with a key
 */
$input = 'I Love PHP!';
$cryptKey = 'PHP4LIFE';
var_dump(Hash::encrypt($input, $cryptKey));

/*
 * Decrypt a string input with a key
 */
$input = Hash::encrypt($input, $cryptKey);
$cryptKey = 'PHP4LIFE';
var_dump(Hash::decrypt($input, $cryptKey));
