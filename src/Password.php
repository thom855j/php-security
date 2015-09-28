<?php
namespace WebSupportDK\PHPSecurity;

class Password
{

    /*
     * Hash a password with a salt
     */
    public static
            function hash($input, $rounds = 7)
    {
        $salt       = "";
        $salt_chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        for ($i = 0; $i < 22; $i++)
        {
            $salt .= $salt_chars[array_rand($salt_chars)];
        }
        return crypt($input, sprintf('$2y$%02d$', $rounds) . $salt);
    }

    /*
     * Verify that the input is indeed the hashed password
     */
    public static
            function verify($input, $data)
    {
        return crypt($input, $data) === $data;
    }

}
