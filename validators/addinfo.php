<?php

namespace validators;

use validators\Validators;


//class must implemnent Validators Interface
class addinfo implements Validators
{


    function __construct()
    {
     
    }

   

    /**
     * @param post contains values from $_POST
     */
    static function  validate($post)
    {

        $errors = [];
        if (!isset($post['dob'])) {

            $errors['dob'][] = 'Date of Birth is Required';
        }

        if (!isset($post['address'])) {
            $errors['address'][] = 'Address is required';
        }

        if (count($errors) > 0) {
            return $errors;
        }
      
        if(empty($post['address'])){
            $errors['address'][] = 'Address is required';
        }

         
        if(empty($post['dob'])){
            $errors['dob'][] = 'Date of birth is required';
        }




        $d = date_parse($_POST['dob']);

       if($d['error_count']>0) {
        $errors['dob'][] = 'Invalid date';
       }  

        return $errors;
    }
}
