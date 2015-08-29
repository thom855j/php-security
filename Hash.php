<?php
namespace thom855j\security;

class Hash
{

    public static
            function makeCookieHash($string, $salt = '')
    {
        return hash('sha256', $string . $salt);
    }

    public static
            function rand($length)
    {
        $chars = '~)!abc}def#ghijkl[m-no.pqrs]tu;v|wx+yzA%BC(D:EF{GHI&JKLM=NOP*QRS?TUVWXYZ_0123456789';
        $str   = '';
        $size  = strlen($chars);
        for ($i = 0; $i < $length; $i++)
        {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }

    public static
            function unique($salt = null)
    {
        return self::makeCookieHash(uniqid(), $salt);
    }

    public static
            function encrypt($input, $cryptKey)
    {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), $input, MCRYPT_MODE_CBC, md5(md5($cryptKey))));
    }

    public static
            function decrypt($input, $cryptKey)
    {
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), base64_decode($input), MCRYPT_MODE_CBC, md5(md5($cryptKey))), "\0");
    }

}
