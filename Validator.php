<?php

namespace thom855j\php_security;

class Validator
{

    // object instance
    private static
            $_instance = null;
    // containers
    private
            $_passed          = false,
            $_errors          = array(),
            $_storage         = null;
    public
            $_messages         = array();

    public
            function __construct($storage = null)
    {
        $this->_messages = array(
            0  => '.',
            1  => ' is required',
            2  => ' must contain ',
            3  => ' characters',
            4  => ' only ',
            5  => ' needs to match ',
            6  => ' already exists',
            7  => ' is invalid.',
            8  => ' Must only contain letters and numbers.',
            9  => ' must not contain spaces.',
            10 => ' must be a number.'
        );
        $this->_storage  = $storage;
    }

    /*
     * Instantiate object
     */

    public static
            function load($storage = null)
    {
        if (!isset(self::$_instance))
        {
            self::$_instance = new Validator($storage);
        }
        return self::$_instance;
    }

    /*
     * Check input ($_POST) for specific parameters
     */

    public
            function checkPost($source, $items = array())
    {
        $this->_passed = false;
        foreach ($items as $item => $rules)
        {
            foreach ($rules as $rule => $rule_value)
            {

                $value = $source[$item];
                $item  = $item;

                if ($rule === 'required' && empty($value))
                {

                    $this->addError(ucfirst($item) . $this->_messages[1]);
                }
                else if (!empty($value))
                {
                    switch ($rule)
                    {
                        case 'min':
                            if (strlen($value) < $rule_value)
                            {
                                $this->addError(ucfirst($item) . $this->_messages[2] . $rule_value . $this->_messages[3]);
                            }
                            break;

                        case 'max':
                            if (strlen($value) > $rule_value)
                            {
                                $this->addError(ucfirst($item) . $this->_messages[2] . $this->_messages[4] . $rule_value . $this->_messages[3]);
                            }
                            break;

                        case 'matches':
                            if ($value != $source[$rule_value])
                            {
                                $this->addError(ucfirst($rule_value) . $this->_messages[5] . $item);
                            }
                            break;

                        case 'notTaken':
                            $check = $this->_storage->get(array($item), $rule_value, array(array($item, '=', $value)));
                            if ($check->count())
                            {
                                $this->addError($item . $this->_messages[5]);
                            }
                            break;

                        case 'validEmail':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL))
                            {
                                $this->addError("This {$item} is invalid. Must be like email@mail.com.");
                            }
                            break;

                        case 'validInput':
                            if (!preg_match('/^[0-9A-Za-z\æ\ø\å\s]{3,}$/', $value))
                            {
                                $this->addError($item . $this->_messages[7] . $this->_messages[8]);
                            }
                            break;

                        case 'noSpaces':
                            if (strpos($value, ' '))
                            {
                                $this->addError($item . $this->_messages[9]);
                            }
                            break;

                        case 'validNumber':
                            if (!is_numeric($value))
                            {
                                $this->addError("{$item} must be a number.");
                            }
                            break;
                    }
                }
            }
        }

        $this->_passed = (empty($this->_errors)) ? true : false;

        return $this;
    }

    public
            function checkFile($source, $options = array())
    {
        $file_name = array_keys($options);
        $filename  = $file_name[0];

        $this->_passed = false;
        $count         = 0;

        // Loop $_FILES to exeicute all files
        foreach ($_FILES[$filename]['name'] as $f => $name)
        {
            if ($_FILES[$filename]['error'][$f] == 4)
            {
                continue; // Skip file if any error found
            }
            if ($_FILES[$filename]['error'][$f] == 0)
            {
                $names[]  = $name;
                $ext[] = pathinfo($name, PATHINFO_EXTENSION);
                $size[] = $_FILES[$filename]['size'][$f];
                $count++; // Number of successfully uploaded file
            }
        }


        foreach ($options as $option => $rules)
        {
            foreach ($rules as $rule => $rule_value)
            {

                if (!empty($options))
                {
                    switch ($rule)
                    {
                        case 'ext':
                            foreach ($ext as $value)
                            {
                                if (!in_array($value, $rule_value))
                                {
                                    $this->addError("{$value} extension not allowed! Please use " . implode(', ', $rule_value). ". ");
                                }
                            }
                            break;

                        case 'size':
                            foreach ($size as $value)
                            {
                                if ($value > $rule_value)
                                {
                                    $this->addError("File is too big! Please use a file less than " . $value);
                                }
                            }
                            break;
                    }
                }
            }
        }

        $this->_passed = (empty($this->_errors)) ? true : false;

        return $this;
    }

    private
            function addError($errors)
    {
        $this->_errors[] = $errors;
    }

    public
            function addMessage($key, $message)
    {
        $this->_messages[] = array($key => $message);
    }

    public
            function errors()
    {
        return $this->_errors;
    }

    public
            function passed()
    {
        return $this->_passed;
    }

}
