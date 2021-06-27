<?php
include '../autoloaders.php'; //importing autoloaders

//check if user is logged in
if (!isset($_SESSION['user'])) {
  header('location: ../login.php');
  return;
}

//get customer object from session
$customer = $_SESSION['user'];


$customer_info = $customer->getDetails(); //get customer information


//if information is not found redirect
if (!$customer_info) {
  unset($_SESSION['user']);
  header('location: login.php');
}

//if customer already has address redirect
if ($customer_info['address'] != NULL) {
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
  <title>Other Info</title>

</head>


<body>

<!-- nav header section of the page -->
  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid justify-content-end">
      <a class="btn btn-outline-warning me-2 color-white" href="../logout.php" type="button">Sign Out</a>

    </div>
  </nav>

  <!--end of  nav header section of the page -->


   <!--form section of the page -->
  <div class="d-flex justify-content-center align-items-center w-100  bg-secondary " style="height:100vh">
    <form class="form-signin" method="post" action="../controllers/customers.php">
      <h4>
        Add Other Information
      </h4>
      <?php include '../alert.php' ?>
      <input name="add_info" hidden />
      <label for="inputEmail" class="sr-only">Date of Birth</label>
      <input type="date" name="dob" class="form-control" required autofocus>
      <label name="address" class="sr-only">Address</label>
      <textarea name="address" class="form-control" placeholder="Address" required></textarea>

      <button class="btn btn-lg btn-success btn-block mt-5" type="submit">Save</button>

    </form>
  </div>
   <!-- end of form section of the page -->


  <?php include 'footer.php'      ?>