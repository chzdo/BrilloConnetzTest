<?php

require_once('../autoloaders.php');  //Require the autoloader for PHP classes

/**
 * Checking to see if admin is logged in to redirect
 * to Dashboard.
 */
if (isset($_SESSION['admin'])) {
    header('location: index.php');
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
    <title>Admin Login</title>

    <style>

    </style>
</head>


<body>

<!-- This is the nav section of the admin login page -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid justify-content-start">
            <a class="btn btn-outline-warning me-2 color-white" type="button">Home</a>

        </div>
    </nav>

<!-- End of Nav section -->


    <div class="d-flex justify-content-center align-items-center w-100  bg-secondary " style="height:100vh">

    <!-- Start of Login form -->
        <form class="form-signin" method="post" action="../controllers/admin.php">
            <?php
               include '../alert.php';

            ?>
            <h1 class="h3 mb-3 font-weight-normal">Admin sign in</h1>
            <label  class="sr-only">ID</label>
            <input type="text" name="id" class="form-control" placeholder="Admin ID" required autofocus>
            <label  class="sr-only">Password</label>
            <input type="password"  class="form-control" name="password" placeholder="Password" required>
            <input type="hidden" name="login" />
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">Sign in</button>

        </form>
          <!-- End of Login form -->
    </div>
<?php 

include 'footer.php'
?>