<?php
require_once('../autoloaders.php'); //Requiring the autoloaders for PHP classes


/**
 * Check if Admin is Logged in!
 * Before accessing this page admin needs to be logged in
 */

if (!isset($_SESSION['admin'])) {
    header('location: login.php');
}




?>
<!-- Start of HTML page -->
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../static/styles.css" rel="stylesheet" />
    <title>Admin</title>

    <style>

    </style>
</head>



<body>

    <!-- Nav section of the page -->
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">


        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="../logout.php">Sign out</a>

            </li>

        </ul>
    </nav>
    <!-- End of Nav section -->


    <!-- Start of  Container of main page -->
    <div class="container-fluid">
        <div class="row">

            <!-- Start of  Side Nav Bar  -->
            <nav class="col-md-2 d-none d-md-block bg-light sidebar bg-dark">
                <div class="sidebar-sticky bg-dark pt-5">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                <span data-feather="index.php"></span>
                                Dashboard <span class="sr-only"></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="customers.php">
                                <span data-feather="file"></span>
                                Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="purchases.php">
                                <span data-feather="shopping-cart"></span>
                                Purchases
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="movies.php">
                                <span data-feather="users"></span>
                                Movies
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="upload.php">
                                <span data-feather="users"></span>
                                Upload Movie
                            </a>
                        </li>
                    </ul>


                </div>
            </nav>

            <!-- End  of  Side Nav Bar  -->


            <!-- Start of Main Page  -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-9 px-4 ml-5 mt-5 " style="margin-left:20%">