<?php

namespace validators;

use validators\Validators;

class password implements Validators
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
     

     
        if (!isset($post['oldpassword'])) {
            $errors['oldpassword'][] = 'Old Password is Required';
        }
        if (!isset($post['newpassword'])) {
            $errors['newpassword'][] = 'new Password is Required';
        }
        if (!isset($post['renewpassword'])) {
            $errors['renewpassword'][] = 'Re New Password is Required';
        }

        if (count($errors) > 0) {
            return $errors;
        }

    
     

    
        
        if (empty($post['oldpassword'])) {
            $errors['oldpassword'][] = 'Old Password is Required';
        }
        if (empty($post['newpassword'])) {
            $errors['newpassword'][] = 'New Password is Required';
        }

        if (empty($post['renewpassword'])) {
            $errors['renewpassword'][] = 'Re New Password is Required';
        }

        if (strlen($post['newpassword']) < 11) {
            $errors['newpassword'][] = 'Password length should be more than 10';
        }

        if (strlen($post['renewpassword']) < 11) {
            $errors['renewpassword'][] = 'Password length should be more than 10';
        }


      


        return $errors;
    }
}
