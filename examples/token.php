<?php

// load file
require_once '../Token.php';

// use namespace
use thom855j\PHPSecurity\Token;

/*
 * Token genereates a random key and puts in in a session
 */
$key = 'CRSF_TOKEN';
Token::generate($key);

/*
 * To see random keys generated
 */
echo Token::show();

/*
 * Checks if token session is set. Usefull for validating forms for CRSF
 */
$key = 'CRSF_TOKEN';
$token = $_SESSION[$key];
if(Token::check($key, $token)){
    echo 'Token accepted!';
} else {
     echo 'CSRF attack!';
}
