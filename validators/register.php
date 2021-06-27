<?php

namespace validators;

use validators\Validators;

class register implements Validators
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

        if (!isset($post['phone_number'])) {
            $errors['phone_number'][] = 'Phone Number is Required';
        }
        if (!isset($post['first_name'])) {
            $errors['first_name'][] = ' first name is Required';
        }
        if (!isset($post['last_name'])) {
            $errors['last_name'][] = ' last name is Required';
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

        if (empty($post['phone_number'])) {
            $errors['phone_number'][] = 'Phone Number is Required';
        }
        if (empty($post['first_name'])) {
            $errors['first_name'][] = ' first name is Required';
        }
        if (empty($post['last_name'])) {
            $errors['last_name'][] = ' last name is Required';
        }
        
        if (empty($post['password'])) {
            $errors['password'][] = 'Password is Required';
        }





        if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = 'Invalid Email Address';
        }

        if (!preg_match("/^[0-9]{11}$/", $post['phone_number'])) {
            $errors['phone_number'][] = 'Invalid Phone Number';
        }

     
        if (strlen($post['password']) < 11) {
            $errors['password'][] = 'Password must be more than 10 characters';
        }

        return $errors;
    }
}
