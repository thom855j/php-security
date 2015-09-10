<?php

// load file
require_once '../src/Cookie.php';

// use namespace
use thom855j\PHPSecurity\Cookie;

/*
* Check if cookie exists by name
*/
$name = 'Cokiemonster';
Cookie::exists($name);

/*
 * Get cookie value by name
 */
$name = 'Cokiemonster';
Cookie::get($name);

/*
 * Set a cookie and exipiry
 */
$name = 'Cokiemonster';
$value = 'R/d7/(S#*p=';
$expiry = 1800;
Cookie::set($name, $value, $expiry);

/*
 * Delete cookie by setting expiry to zero
 */
$name = 'Cokiemonster';
Cookie::delete($name);
