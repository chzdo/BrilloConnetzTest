<?php

namespace validators;

use validators\Validators;

class login implements Validators
{


    function __construct($post)
    {
        $this->post = $post;
    }

 /**
     * @param post contains values from $_POST
     */
    static function  validate($post)
    {

        
        $errors = [];
        if (!isset($post['email'])) {

            $errors['email'][] = 'Email is Required';
        }

     
        if (!isset($post['password'])) {
            $errors['password'][] = 'Password is Required';
        }

        if (count($errors) > 0) {
            return $errors;
        }

    
        if (empty($post['email'])) {

            $errors['email'][] = 'Email is Required';
        }

    
        
        if (empty($post['password'])) {
            $errors['password'][] = 'Password is Required';
        }





        if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = 'Invalid Email Address';
        }


        return $errors;
    }
}
