<?php

namespace Datalaere\PHPSecurity;

class Validator
{

    // object instance
    private static $_instance = null;

    // containers
    private
            $passed = false,
            $errors = array(),
            $db = null;

    public $feedback = array();

    public function __construct($db = null)
    {
        $this->feedback = array(
            'req'     => ' is required.',
            'min'     => ' must min contain ',
            'max'     => ' must max contain ',
            'chars'   => ' characters. ',
            'match'   => ' needs to match ',
            'exists'  => ' already exists.',
            'invalid' => ' is invalid.',
            'input'   => ' Must only contain letters and numbers. ',
            'spaces'  => ' must not contain spaces.',
            'number'  => ' must be a number.',
            'ext'     => ' extension not allowed! Please use ',
            'size'    => 'File is too big! Please use a file less than '
        );

        $this->db = $db;
    }

    /*
     * Instantiate object
     */
    public static function load($db = null)
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Validator($db);
        }

        return self::$_instance;
    }

    public function setAttribute($attribute, $name)
    {
        $this->$attribute = $name;
    }

    /*
     * Check input ($_POST) for specific parameters
     */
    public function checkPost($source, $items = array())
    {
        $this->passed = false;
        foreach ($items as $item => $rules) {

            foreach ($rules as $rule => $rule_value) {

                $value = $source[$item];
                $item  = $item;

                if ($rule === 'required' && empty($value)) {

                    $this->addError(ucfirst($item) . $this->feedback['req']);

                } else if (!empty($value)) {

                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value)  {
                                $this->addError(
                                    ucfirst($item) 
                                    . $this->feedback['min'] 
                                    . $rule_value 
                                    . $this->feedback['chars']
                                );
                            }
                            break;

                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError(
                                    ucfirst($item) 
                                    . $this->feedback['max'] 
                                    . $rule_value 
                                    . $this->feedback['chars']
                                );
                            }
                            break;

                        case 'matches':
                            if ($value != $source[$rule_value])  {
                                $this->addError(
                                    ucfirst($rule_value) 
                                    . $this->feedback['match'] 
                                    . $item
                                );
                            }
                            break;

                        case 'notTaken':
                            $check = $this->db->select(
                                array($item), 
                                $rule_value,
                                null, 
                                array(array($item, '=', $value))
                            );

                            if ($check->count()) {
                                $this->addError("'$value'" . $this->feedback['exists']);
                            }
                            break;

                        case 'validEmail':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError(ucfirst($item) . $this->feedback['invalid']);
                            }
                            break;

                        case 'validInput':
                            if (!preg_match('/^[0-9A-Za-z\æ\ø\å\s]{3,}$/', $value))  {
                                $this->addError(
                                    ucfirst($item) 
                                    . $this->feedback['invalid'] 
                                    . $this->feedback['input']
                                );
                            }
                            break;

                        case 'noSpaces':
                            if (strpos($value, ' ')) {
                                $this->addError(
                                    ucfirst($item) 
                                    . $this->feedback['spaces']
                                );
                            }
                            break;

                        case 'validNumber':
                            if (!is_numeric($value)) {
                                $this->addError(ucfirst($item) . $this->feedback['number']);
                            }
                            break;
                    }
                }
            }
        }

        $this->passed = (empty($this->errors)) ? true : false;

        return $this;
    }

    public function checkFile($source, $options = array())
    {
        $file_name = array_keys($options);
        $filename  = $file_name[0];

        if (!empty($source[$filename]['name'][0])) {

            $this->passed = false;
            $count        = 0;

            // Loop $_FILES to exeicute all files
            foreach ($source[$filename]['name'] as $f => $name) {

                if ($source[$filename]['error'][$f] == 4) {
                    continue; // Skip file if any error found
                }

                if ($source[$filename]['error'][$f] == 0) {
                    $names[] = $name;
                    $ext[]   = pathinfo($name, PATHINFO_EXTENSION);
                    $size[]  = $source[$filename]['size'][$f];
                    $count++; // Number of successfully uploaded file
                }
            }


            foreach ($options as $option => $rules) {

                foreach ($rules as $rule => $rule_value) {

                    if (!empty($options)) {

                        switch ($rule) {
                            case 'ext':
                                foreach ($ext as $value) {
                                    if (!in_array($value, $rule_value)) {
                                        $this->addError(
                                            "'.{$value}'" 
                                            . $this->feedback['ext'] 
                                            . implode(', ', $rule_value)
                                        );
                                    }
                                }
                                break;

                            case 'size':
                                foreach ($size as $value) {
                                    if ($value > $rule_value) {
                                        $this->addError($this->feedback['size'] . $this->bytesToSize($value));
                                    }
                                }
                                break;
                        }
                    }
                }
            }

            $this->passed = (empty($this->errors)) ? true : false;

            return $this;
        }
        return false;
    }

    public function bytesToSize($bytes, $precision = 2, $powers = 1000)
    {
        // human readable format -- powers of 1024
        $unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');

        return @round(
            $bytes / pow($powers, ($i = floor(log($bytes, $powers)))), 
            $precision
            ) . ' ' . $unit[$i];
    }

    private function addError($errors)
    {
        $this->errors[] = $errors;
    }

    public function setFeedback($key, $feedback)
    {
        $this->feedback[$key] = $feedback;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function passed()
    {
        return $this->passed;
    }

}
