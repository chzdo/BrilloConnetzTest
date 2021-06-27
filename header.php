<?php

include 'autoloaders.php'

?>

<!doctype html>
<html lang="en">


<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href="static/styles.css" rel="stylesheet" />
  <title>Movie App!</title>

  <style>

  </style>
</head>


<body>

<!-- Nav bar  section of the page  -->
  <nav class="navbar navbar-dark bg-dark">


    <div class="container-fluid justify-content-space-between">
      <a class="btn btn-outline-primary me-2 color-white" href="index.php" type="button">Back</a>

      <?php

      if (isset($_SESSION['user'])) { ?>


        <a class="btn btn-outline-success me-2 color-white" href="dashboard/" type="button">Dashboard</a>

      <?php } else {
      ?>
        <a class="btn btn-outline-success me-2 color-white" href="login.php" type="button">Sign IN</a>
      <?php     }  ?>

    </div>
  </nav>
  <!-- end of Nav bar  section of the page  -->