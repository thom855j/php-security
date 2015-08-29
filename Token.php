<?php
namespace thom855j\security;

class Token
{

    public static
            function generate($key, $length = 32)
    {

        return $_SESSION[$key] = base64_encode(openssl_random_pseudo_bytes($length));
    }

    public static
            function show($length = 32)
    {
        return base64_encode(openssl_random_pseudo_bytes($length));
    }

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
