<?php
namespace thom855j\Security;

class Session
{

    /**
     * starts a session
     */
    public static
            function init()
    {
        // if no session exist, start a new session
        if (session_id() == '')
        {
            session_start();
        }
    }

    /*
     * Check if session exists or not
     */
    public static
            function exists($name)
    {
        return (isset($_SESSION[$name])) ? true : false;
    }
    
    /*
     * Set a session by key and value
     */
    public static
            function set($key, $value)
    {
        return $_SESSION[$key] = $value;
    }

    /**
     * Adds a value as a new array element to the key.
     * useful for collecting error messages etc
     *
     * @param mixed $key
     * @param mixed $value
     */
    public static
            function add($key, $value)
    {
        $_SESSION[$key][] = $value;
    }

    /*
     * Get a session value by key
     */
    public static
            function get($key)
    {
        return $_SESSION[$key];
    }
    
/*
 * Get a sessions array value by key and value
 */
    public static
            function getKey($key, $name)
    {
        return $_SESSION[$key][$name];
    }

    /*
     * Delete key value from session array
     */
    public static
            function deleteKey($key, $value)
    {
        unset($_SESSION[$key][$value]);
    }

    /*
     * Arra push to arrays together
     */
    public static
            function push($key, $value)
    {
        return array_push($_SESSION[$key], $value);
    }

    /*
     * Delete session by key name
     */
    public static
            function delete($key)
    {
        if (self::exists($key))
        {
            unset($_SESSION[$key]);
        }
    }

    /**
     * deletes the session (= logs the user out)
     */
    public static
            function destroy()
    {
        session_destroy();
    }

    /*
     * Flash messages by deleting session after it is shown
     */
    public static
            function flash($key, $string = null)
    {
        if ((self::exists($key)))
        {
            $session = self::get($key);
            self::delete($key);
            return $session;
        }
        else
        {
            self::set($key, $string);
        }
    }

    public static
            function check($expiry = 1800)
    {
        //Check for user activity
        if (self::exists('LAST_ACTIVITY') && (time() - self::get('LAST_ACTIVITY')
                > $expiry))
        {
            // last request was more than 30 minutes ago
            session_unset();     // unset $_SESSION variable for the run-time
            session_destroy();   // destroy session data in storage
        }

        self::set('LAST_ACTIVITY', time()); // update last activity time stamp

        if (!self::exists('CREATED'))
        {
            self::set('CREATED', time());
        }
        else if (time() - self::get('CREATED') > $expiry)
        {
            // session started more than 30 minutes ago
            session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
            self::set('CREATED', time()); // update creation time
        }
    }

}
