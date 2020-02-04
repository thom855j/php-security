<?php

namespace PHP\Security;

class Crypt
{

    /*
    * Encrypt a string input with a key
    */
    public static function encrypt($input, $cryptKey)
    {
        return base64_encode(
            mcrypt_encrypt(
                MCRYPT_RIJNDAEL_256, 
                md5($cryptKey), 
                $input, 
                MCRYPT_MODE_CBC, 
                md5(
                    md5($cryptKey)
                )
            )
        );
    }

    /*
    * Decrypt an encrypted string with a key
    */
    public static function decrypt($input, $cryptKey)
    {
        return rtrim(
            mcrypt_decrypt(
                MCRYPT_RIJNDAEL_256, 
                md5($cryptKey), 
                base64_decode($input), 
                MCRYPT_MODE_CBC, 
                md5(
                    md5($cryptKey)
                )
            ), "\0"
        );
    }

}