
<?php

use Models\Customer;

require_once('../autoloaders.php'); //requiring autoladers for PHP classes


//check if user is logged in
if(!isset($_SESSION['user'])){

    header('location: ../login.php');
    return;
}


$customer = $_SESSION['user'];  //get the object user from session

$customer_info = $customer->getDetails(); //get user information


//if no information is found unset session and redirect
if(!$customer_info){
      unset($_SESSION['user']);
     header('location: login.php');
}


//if user do not have an address redirect to fill address
if($customer_info['address'] == NULL ){
 
   header('location: otherinfo.php');
   return;
}


//if user do not have an card information redirect to add card
if($customer_info['tokens'] == NULL){
    header('location: addcard.php');
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
    <title>Dashboard</title>

    <style>

    </style>
</head>


<body>

<!-- nav header section of the page -->
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">          
        
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="../logout.php">Sign out</a>
             
            </li>
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="../index.php">home</a>
             
            </li>
        </ul>
    </nav>
<!-- end of nav header section of the page -->


<!-- main container  of the page -->
    <div class="container-fluid">
        <div class="row">

        <!-- nav side bar section of the page -->
            <nav class="col-md-2 d-none d-md-block bg-light sidebar bg-dark">
                <div class="sidebar-sticky bg-dark pt-5">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">
                                <span data-feather="home"></span>
                                Dashboard 
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="purchases.php">
                                <span data-feather="file"></span>
                                Purchases
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cards.php">
                                <span data-feather="shopping-cart"></span>
                             Cards
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="edit.php">
                                <span data-feather="users"></span>
                                Edit Profile
                            </a>
                        </li>
                   
                    </ul>

                  
                </div>
            </nav>
        <!-- end of  nav side bar  section of the page -->
        
            <main role="main" class="col-md-9 ml-sm-auto col-lg-9 px-4 ml-5 mt-5 " style="margin-left:20%" >