<?php
namespace thom855j\security;

class Validater
{

    // containers
    private
            $_passed = false,
            $_errors = array(),
            $_storage = null;
    // Error messages
    private
            $_required,
            $_min;

    public
            function __construct($storage, $required = null, $min = null)
    {
        $this->_storage = $storage;
        $this->_required = ' is required.';
        $this->_min = ' is required.';
    }

    public
            function check($source, $items = array())
    {
        $this->_passed = false;
        foreach ($items as $item => $rules)
            {
            foreach ($rules as $rule => $rule_value)
                {

                $value = $source[$item];
                $item = $item;

                if ($rule === 'required' && empty($value))
                    {

                    $this->addError(ucfirst($item) . $this->_required);
                    }
                else if (!empty($value))
                    {
                    switch ($rule)
                        {
                        case 'min':
                            if (strlen($value) < $rule_value)
                                {
                                $this->addError(ucfirst($item) . " must contain {$rule_value} characters.");
                                }
                            break;

                        case 'max':
                            if (strlen($value) > $rule_value)
                                {
                                $this->addError("<p class='error'><span>" . ucfirst($item) . "</span> must contain only {$rule_value} characters</p>");
                                }
                            break;

                        case 'matches':
                            if ($value != $source[$rule_value])
                                {
                                $this->addError("<p class='error'><span>" . ucfirst($rule_value) . "</span> needs to match {$item}</p>");
                                }
                            break;

                        case 'notTaken':
                            $check = $this->_storage->get(array($item),
                                    $rule_value, array($item, '=', $value));
                            if ($check->count())
                                {
                                $this->addError("<p class='error'><span>{$item}</span> already exists</p>");
                                }
                            break;

                        case 'validNumber':
                            if (!filter_var($value, FILTER_VALIDATE_INT))
                                {
                                $this->addError("<p class='error'>This date is not valid. Must be numeric.</p>");
                                }
                            break;

                        case 'validPass':
                            if (!ctype_alnum($value))
                                {
                                $this->addError("<p class='error'>{$item} is not a valid password. Must only contain letters and numbers.</p>");
                                }
                            break;

                        case 'validEmail':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL))
                                {
                                $this->addError("<p class='error'>This {$item} is invalid. Must be like email@mail.com</p>");
                                }
                            break;

                        case 'validInput':
                            if (!filter_var($value, FILTER_VALIDATE_URL))
                                {
                                $this->addError("This {$item} is invalid.</p>");
                                }
                            break;

                        case 'validDate':
                            $date = $value;

                            $date = explode('-', $date);

                            $date = checkdate($date[1], $date[2], $date[0]);

                            if (!$date)
                                {
                                $this->addError("<p class='error'>Date is invalid</p>");
                                }
                            break;

                        case 'validNumber':
                            if (!is_numeric($value))
                                {
                                $this->addError("<p class='error'>{$item} must be a number</p>");
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
            function checkImage($source, $item, $options = array())
    {
        $this->_passed = false;
        foreach ($options as $option => $rules)
            {
            foreach ($rules as $rule => $rule_value)
                {

                $value = trim($source[$item][$option]);
                $option = $option;

                if (!empty($value))
                    {
                    switch ($rule)
                        {
                        case 'ext':
                            $extension = explode(".", $value);
                            $file_extension = end($extension);
                            if (!in_array($file_extension, $rule_value))
                                {
                                $this->addError("<p style='color: red;' class='error'>'.{$file_extension}' extension not allowed! Please use " . implode(', ',
                                                $rule_value) . ".</p>");
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
