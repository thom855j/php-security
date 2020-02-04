<?php

namespace PHP\Security;

class Hash
{

    /*
     * Create a random hash string
     */
    public static function rand($length)
    {
        $chars = '~)!abc}def#ghijkl[m-no.pqrs]tu;v|wx+yzA%BC(D:EF{GHI&JKLM=NOP*QRS?TU4567VWXYZ_012389' .uniqid();
        $str   =  '';
        $size  = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        
        return $str;
    }

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
