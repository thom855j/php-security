<?php
namespace WebSupportDK\PHPSecurity;

class Token
{
    
/*
 * Token genereates a random key and puts in in a session
 */
    public static
            function generate($key, $length = 32)
    {

        return $_SESSION[$key] = base64_encode(openssl_random_pseudo_bytes($length));
    }

    /*
     * To see random keys generated
     */
    public static
            function create($length = 32)
    {
        return base64_encode(openssl_random_pseudo_bytes($length));
    }
    
    /*
     * Checks if token session is set. Usefull for validating forms for CRSF
     */

    public static
            function check($key, $token)
    {

        if (isset($_SESSION[$key]) && $token === $_SESSION[$key])
        {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }

}
