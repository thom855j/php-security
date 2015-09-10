<?php

// load file
require_once '../src/Session.php';

// use namespace
use thom855j\PHPSecurity\Session;

/*
 * Init session (session start and check if session is already set
 */
Session::init();

/*
 * Checks if session exists
 */
$name = 'example';
if (Session::exists($name))
{
    echo 'yes';
}
else
{
    echo 'no';
}

/*
 * Set a session by key and value
 */
$key   = 'example';
$value = 'I Love PHP';
Session::set($key, $value);

/*
 * Adds a value as a new array element to the key.
 * useful for collecting error messages etc
 */
$key   = 'example';
$value = 'I Love PHP';
Session::add($key, $value);

/*
 * Get a session value by key
 */
$key = 'example';
Session::get($key);

/*
 * Get a sessions array value by key and value
 */
$key   = 'example';
$name = 0;
Session::getKey($key, $name);

/*
 * Get a sessions array value by key and value
 */
$key   = 'example';
$value = 'I Love PHP';
Session::deleteKey($key, $value);

/*
 * Delete session by key name
 */
$key = 'example';
Session::delete($key);

/**
* deletes the session (= logs the user out)
*/
Session::destroy();

 /*
* Flash messages by deleting session after it is shown
*/
$key = 'SUCCESS';
$string = 'Login is success!';
Session::flash($key, $string);

/*
 * Check session inacktivity. Set exipiry in time format
 */
$expiry = 1800;
Session::check($expiry);

var_dump($_SESSION);
