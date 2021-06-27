<?php
include '../autoloaders.php';

//check if user is logged in 
if (!isset($_SESSION['user'])) {
  header('location: ../login.php');
  return;
}


$customer = $_SESSION['user'];  //getting the customer object from session

$customer_info = $customer->getDetails(); //getting the information of the customer


//if no information is found unset the user session and redirect
if (!$customer_info) {
  unset($_SESSION['user']);
  header('location: login.php');
}


//if customer already has payment tokens redirect
if ($customer_info['tokens'] != NULL) {
  header('location: index.php');
  return;
}




?>


<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href="../static/styles.css" rel="stylesheet" />
  <title>Hello, world!</title>

  <style>

  </style>
</head>

<body>
  <!-- Nav header section of the page -->
  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid justify-content-end">
      <a class="btn btn-outline-warning me-2 color-white" href="../logout.php" type="button">Sign Out</a>

    </div>
  </nav>
 <!--  end ofNav header section of the page -->


 <!-- form section of the page -->
  <div class="d-flex justify-content-center align-items-center w-100  bg-secondary " style="height:100vh">
    <form class="form-signin" method="post" action="../controllers/payment.php">
      <?php include '../alert.php'  ?>
      <input name="addCard" hidden />

      <p class="fw-5">
        You are required to add a card for payment. A token of 50 Naira will be deducted from you account. This is to confirm that your card is working
      </p>
      <button class="btn btn-lg btn-success btn-block mt-5" type="submit">Add Card</button>

    </form>
  </div>

 <!-- end form section of the page -->

<?php 


include 'footer.php' 

?>