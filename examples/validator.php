<?php
if (isset($_POST['submit']))
{
    // load file
    require_once '../src/Validator.php';

// instanciate object.
// optional, if you are going to check for username etc. is taken
// you have to inject a db class in the constructor.
    $v = new thom855j\PHPSecurity\Validator();

// all error messages are returned with the name of the input field

    /*
     * Form validation for $_POST
     */
//choose what source to validate and the items to validate
    $v->checkPost($_POST, array(
        // chose the input name field to set rules for
        'username' => array(
            // the field is required
            'required' => true,
            // min length
            'min'      => 8,
            // max length
            'max'      => 20,
            // the username is NOT in db
            'notTaken' => 'users'
        ),
        'password' => array(
            'required'   => true,
            'min'        => 8,
            'max'        => 20,
            // check if the input is valid and does not contain script tags
            'validInput' => $_POST['password']
        ),
        'email'    => array(
            'required'   => true,
            'min'        => 8,
            'max'        => 30,
            // check if the email is valid and contains @
            'validEmail' => $_POST['email'],
            'notTaken'   => 'users'
        ),
    ));

    /*
     * Form validation for $_FILES
     */
    $v->checkFile($_FILES, array(
        // name of file input field
        'files' => array(
            //allowed extensions for files
            'ext'  => array('png','jpg','jpeg'),
            //set allowed size in bytes
            'size' => 20
        )
    ));

    // if validation did not pass (there was errors somewhere
    if (!$v->passed())
    {
        // gather the errorrs and echo them out
        foreach ($v->errors() as $error)
        {
            echo $error;
        }
    }
}
