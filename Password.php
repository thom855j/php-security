<?php
namespace thom855j\security;

class Password
{

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

    public static
            function verify($input, $data)
    {
        return crypt($input, $data) === $data;
    }

    public static
            function check($input, $data)
    {
        return crypt($input, $data);
    }

}
