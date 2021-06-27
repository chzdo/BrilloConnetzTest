<?php

include '../autoloaders.php';

//Pay Stack URL endpoints
const INIPayment =  "https://api.paystack.co/transaction/initialize";
const VERPayment = "https://api.paystack.co/transaction/verify/";
const CHRPayment = "https://api.paystack.co/transaction/charge_authorization";

//User must be logged in to access this controllers
if (!isset($_SESSION['user'])) {
  header('location: ../login.php');
 return;

}

$customer = $_SESSION['user'];

$email = $customer->getEmail();
if ($email == NULL) {
  header('location: ../login.php');
 return;
}

//Controller for testing card payments

if (isset($_POST['addCard'])) {

  //Checking if user is logged!
 

 
  $fields = [
    'email' => $email,
    'amount' => "5000",
   ];

   /**
    * Using curl to make request to Paysyack endpoint
    */
  $fields_string = http_build_query($fields);
  //open connection
  $ch = curl_init();

  //set the url, number of POST vars, POST data
  curl_setopt($ch, CURLOPT_URL, INIPayment);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer sk_test_df4bf7201fe01e03d0f432f11c1144a2029127ba",
    "Cache-Control: no-cache",
  ));

  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  //execute post
  $result = curl_exec($ch);
  $response = json_decode($result);

  if ($response) {
    header('location: ' . ($response->data->authorization_url)); //routng to paystack check out
    return;
  }
  $_SESSION['msg'] = ['code'=>404, 'message'=> "Could not start  Paystack"];

  header('location: '.$_SERVER['HTTP_REFERER']);

  return;
}




//Controller for making payments for movies
if (isset($_POST['pay'])) {

  
  //check if user has already paid for this movie
  if($customer->checkPurchase($_POST['movie_id'])){
    $_SESSION['msg'] = ['code'=> 400, 'message'=> 'You have already bougth this film'];
    header('location: '.$_SERVER['HTTP_REFERER']);
    return;
  }

//setting up payment paramaters
  $_POST['email'] = $email;
  $_POST['authorization_code'] = $customer->getCardDetails()['tokens'];

  $_POST['metadata'] = [
    "movie_id" => $_POST['movie_id']
  ];

  $fields = $_POST;

  $fields_string = http_build_query($fields);
//end of setting up parameters

  //open connection
  $ch = curl_init();

  //set the url, number of POST vars, POST data
  curl_setopt($ch, CURLOPT_URL, CHRPayment);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer sk_test_df4bf7201fe01e03d0f432f11c1144a2029127ba",
    "Cache-Control: no-cache",
  ));

  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  //execute post
  $result = curl_exec($ch);
  $response = json_decode($result);

  //if response is set redirect to customer  controller for processing
  if ($response) {
    header('location: customers.php?status=' . serialize($response->data) . '&email=' . $response->data->customer->email . '&purchase=movie');
     return;
  }


  $_SESSION['msg'] = ['code'=>404, 'message'=> "Could not start  Paystack"];
  header('location: '.$_SERVER['HTTP_REFERER']);

  return;
}




//callback after test payment
if (isset($_GET['reference'])) {
  //open connection

  $ch = curl_init();

  //set the url, number of POST vars, POST data

  curl_setopt($ch, CURLOPT_URL, VERPayment . $_GET['reference']);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer sk_test_df4bf7201fe01e03d0f432f11c1144a2029127ba",
    "Cache-Control: no-cache",
  ));

  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  //execute post
  $result = curl_exec($ch);
  $error = curl_error($ch);
  $response = json_decode($result);

  //if response redirect to customer controller for processing
  if ($response) { 
             header('location: customers.php?status=' . serialize($response->data) . '&email=' . $response->data->customer->email . '&card=' . serialize($response->data->authorization));
         return;
        
  
  }

  $_SESSION['msg'] = ['code'=>404, 'message'=> "Could not start  Paystack"];
  header('location: '.$_SERVER['HTTP_REFERER']);

  return;
}

