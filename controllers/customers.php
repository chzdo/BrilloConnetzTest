<?php

namespace controllers;

require_once('../autoloaders.php');

use controllers\email;
use \Models\Customer;
use validators\addinfo;
use  \validators\register;
use  \validators\login;
use validators\password;

try {

    /**
     * @param register
     * Route for registeration for customer
     */
    if (isset($_POST['register'])) {
  
        unset($_POST['register']);  //unset form key

        $validate = register::validate($_POST); //Validate Input

        //Check if validation fails
        if (count($validate) > 0) {

            $_SESSION['msg'] = ['code' => 400, "message" => array_values($validate)[0][0]];

            header('location: ../register.php');

            return;
        }

        $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash Password


        $customer = new Customer();  // Create new customer Object

        $reg_response =  $customer->register($_POST); //Call register function

        /**
         * Check response 
         */
        if (!$reg_response) {
            header('location: ../register.php');
            return;
        }

        /**
         * send notification mail
         */

        $status = email::sendMail($_POST['email'], "http://127.0.0.1/test/controllers/customers.php?confirm_mail&email=" . base64_encode($_POST['email']));

        if ($status) {
            $_SESSION['msg']['message'] .= " Check your mail to activate account";
        }

        /**
         * Return to view
         */
        header('location: ../register.php');


        return;
    }


    /**
     * @param login
     * Route for login for customer
     */

    if (isset($_POST['login'])) { //Case Login Controller

        unset($_POST['login']);


        $validate = login::validate($_POST); //Validate Input Login

        if (count($validate) > 0) {

            $_SESSION['msg'] = ['code' => 400, "message" => array_values($validate)[0][0]];

            header('location: ../register.php');

            return;

         
        }


        $resp =   (new Customer())->login($_POST); // Call Login Function


        if ($resp === false) {
            /**
             * if user email was not found
             */
            $v =  $_SESSION['msg'] = ['code' => 404, 'message' => 'User not found'];
            header('location: ../login.php');
            return;
        }

        /**
         * Verifying that user password matches
         */
        if (!password_verify($_POST['password'], $resp['password'])) {
            $v =  $_SESSION['msg'] = ['code' => 404, 'message' => 'Incorrect Password'];
            header('location: ../login.php');
            return;
        }

        /**
         * Checking if account is verified!
         */
        if (!$resp['status']) {
            $v =  $_SESSION['msg'] = ['code' => 403, 'message' => 'You have not activated your account'];
            header('location: ../login.php');
            return;
        }

        $_SESSION['user'] = new Customer($resp['id']); //Creating a session for user

        //Returning to view
        if(isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])){
            header('location: '.$_POST['redirect_url']);
            return;
        }
        header('location: ../dashboard/');
        return;
    }

        /**
     * @param confirm_email
     * Route for confirm email for customer
     */

    if (isset($_GET['confirm_mail'])) { //Case Login Controller

      
         unset($_GET['confirm_mail']);

      


        $resp =   (new Customer())->confirmMail($_GET); // Call Login Function

            
        if ($resp == false) {
            /**
             * if user email was not found
             */
            $v =  $_SESSION['msg'] = ['code' => 404, 'message' => 'User not found'];
            header('location: ../login.php');
            return;
        }

       
        $_SESSION['msg'] = ['code' => 200, "message" => "Email Confimed"]; //Returning to view
        header('location: ../login.php');
        return;
    }

   

    if (!isset($_SESSION['user'])) {

        $_SESSION['msg'] = ['code' => 403, "message" => "No Authorization"];
        header('location:  '.$_SERVER['HTTP_REFERER']);
        return;
    }


    $customer = $_SESSION['user'];

 /**
     * @param add_info
     * Route for adding info for customer
     */

    if (isset($_POST['add_info'])) {

        unset($_POST['add_info']);
        //Checking if user is logged in
      
        //Validation
        $validate = addinfo::validate($_POST);

        if (count($validate) > 0) {
            $_SESSION['msg'] = ['code' => 400, "message" => array_values($validate)[0][0]];

            header('location:  '.$_SERVER['HTTP_REFERER']);

            return;
        }

        if (!$customer->addOtherInfo($_POST)) { //Calling the addOtherInfo function
            echo  json_encode($_SESSION['msg'] = ['code' => 500, "message" => "Something went wrong"]);
            header('location:  '.$_SERVER['HTTP_REFERER']);
        }

      
        header('location:  '.$_SERVER['HTTP_REFERER']);
        return;
    }

    if (isset($_POST['update_name'])) {

        unset($_POST['update_name']);
        //Checking if user is logged in
    
   

        if (!$customer->updateName($_POST)) { //Calling the addOtherInfo function
            echo  json_encode($_SESSION['msg'] = ['code' => 500, "message" => "Something went wrong"]);
            header('location:  '.$_SERVER['HTTP_REFERRER']);
        }

        $_SESSION['msg'] = ['code' => 204, "message" => "Update Successfuly"];
        header('location:  ../dashboard/edit.php');
        return;
    }

    /**
     * @param card
     * Route for adding card for payment
     */

    if (isset($_GET['card']) && isset($_GET['email'])) {
       

        //checking if user is logged in
      

        //getting json object from url
        $authorization = unserialize($_GET['card']);
       

        /**
         * Confirming if payment was made by logged user!
         */
        if ($customer->getEmail() !== $_GET['email']) {
            $_SESSION['msg'] = ['code' => 403, "message" => "invalid payment"];
            header('location: ../dashboard/addcard.php');
            return;
        }

        /**
         * setting up input values
         */
        $post['tokens'] = $authorization->authorization_code;
        $post['last4'] = $authorization->last4;
        $post['exp_month'] = $authorization->exp_month;
        $post['exp_year'] = $authorization->exp_year;

        //calling the addCard function
        if (!$customer->addCard($post)) {
           $_SESSION['msg'] = ['code' => 500, "message" => "Something went wrong"];
            header('location: ../dashboard/addcard.php');
            return;
        }

      
        header('location: ../dashboard/');
        return;
    }

    /**
     * @param changePassword
     * Route for for changing password
     */

    if (isset($_POST['changePassword'])) {

      

        $validation = password::validate($_POST); //validation

        if (count($validation) > 0) {
            $_SESSION['msg'] = ['code' => 400, "message" => array_values($validation)[0][0]];

            header('location: ../dashboard/edit.php');

            return;
        }
        $customer = $_SESSION['user'];

        //setting input values
     
        $newpassword = $_POST['newpassword'];
        $renewpassword = $_POST['renewpassword'];

        //Verifying password 
        if (!password_verify($oldpassword, $customer->getPassword())) {
            $_SESSION['msg'] = ['code' => 403, "message" => "Invalid Password"];
            header('location: ../dashboard/edit.php');
            return;
        }
        //Verify if user entered same password 
        if (($newpassword != $renewpassword)) {
            $_SESSION['msg'] = ['code' => 403, "message" => "Password Mismatch"];
            header('location: ../dashboard/edit.php');
            return;
        }



        $hash = password_hash($newpassword, PASSWORD_DEFAULT);  //Hashing password

        //Updating password

        if (!$customer->changePassword($hash)) {
            $_SESSION['msg'] = ['code' => 500, "message" => "Something went wrong"];
            header('location: ../dashboard/edit.php');
            return;
        }

       $_SESSION['msg'] = ['code' => 204, "message" => "Update Successfuly"];
        header('location: ../dashboard/edit.php');
        return;
    }

    /**
     * @param Purchase
     * Route for for purchasing a movie
     */

    if (isset($_GET['purchase'])) {

      

        $authorization = unserialize($_GET['status']); //getting status in url


        //Checking the status of payment

        if ($authorization->status != 'success') {
            $_SESSION['msg'] = ['code' => 403, "message" => "Payment not successfully"];
            header('location: '.$_SERVER['HTTP_REFERER']);
            return;
        }

       
        /**
         * confirming payment was done by logged user
         */

        if ($customer->getEmail() !== $_GET['email']) {
            $_SESSION['msg'] = ['code' => 403, "message" => "invalid payment"];
            header('location: '.$_SERVER['HTTP_REFERER']);
            return;
        }

        /**
         * Add purchase to DB
         */
        if (!$customer->addPurchase($authorization->metadata->movie_id, $authorization->reference,$authorization->amount)) {
            $_SESSION['msg'] = ['code' => 500, "message" => "Something Went wrong"];
            header('location: '.$_SERVER['HTTP_REFERER']);
            return;
        }

     
       
        
        header('location:  ../dashboard/purchases.php');
        return;
    }


    echo  "Invalid Request";
} catch (\Exception $e) {
    echo  $e->getMessage();
}
