<!doctype html>
<html lang="en">

<?php
include('autoloaders.php');
use Models\movies;




$movies = new movies();  //new object of movie class

/**
 * set up variables and values for movies
 */

$list = $movies->getAll("", "", "","");
$nlist = [];
$temp = [];
$genre = [];

//looping through the movies array to restructure
foreach ($list as $value) {

    $temp[$value['movie_title']]['movie'] = $value;
    $temp[$value['movie_title']]['genre'][] = $value['genre_name'];
      $genre[$value['genre_name']] = $value['genre_name'];
 

  
}

$genre = array_keys($genre);  //get all existing genre

 foreach($genre as $gen){

     foreach($temp as $movie){
       
       if(in_array($gen,$movie['genre'])){
          $nlist[$gen][] = $movie;
       }
     
     }
 }



?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href="static/styles.css" rel="stylesheet"  />
    <title>Movies</title>

    <style>
      
    </style>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid justify-content-end">
          <?php 

          if(isset($_SESSION['user'])){?>


            <a class="btn btn-outline-success me-2 color-white" href="dashboard/" type="button">Dashboard</a>

            <?php } else{
              ?>
              <a class="btn btn-outline-success me-2 color-white" href="login.php" type="button">Sign IN</a>
         <?php     }  ?>

        </div>
    </nav>


   
<!-- accordion section of the page -->
<div class="accordion">
  <ul>
    <li>
      <div class="image_title">
        <a href="#">Transformers: The Last Knight</a>
      </div>
    <img src="https://image.ibb.co/k7P0kS/transformers4_640x320.jpg" alt="transformers4_640x320" border="0">
    </li>
    <li>
      <div class="image_title">
        <a href="#">Blade Runner 2049</a>
      </div>
     <img src="https://image.ibb.co/ct9rQS/Blade_Runner2049_640x320.jpg" alt="Blade_Runner2049_640x320" border="0">
    </li>
    <li>
      <div class="image_title">
        <a href="#">Guardians of the Galaxy: Vol. 2</a>
      </div>
      <img src="https://image.ibb.co/jAu0kS/GOG2_640x320.jpg" alt="GOG2_640x320" border="0">
    </li>
    <li>
      <div class="image_title">
        <a href="#">Spiderman: Homecoming</a>
      </div>
     <img src="https://image.ibb.co/da7xX7/spiderman_homecoming_640x320.jpg" alt="spiderman_homecoming_640x320" border="0">
    </li>
    <li>
      <div class="image_title">
        <a href="#">Wonder Woman</a>
      </div>
      <img src="https://image.ibb.co/dHdAkS/Wonder_Woman_640x320.jpg" alt="Wonder_Woman_640x320" border="0">
    </li>
  </ul>
</div>
<!-- end accordion section of the page -->



<!-- Movie cards section of the page  -->
<?php foreach($nlist as $key=>$value){?>

    <div class=" mt-5 w-100 bg-dark  p-1" style="display:flex; justify-content:space-between" style="align-items:center">
        <h3 class="ml-5 h6  pl-5 color-white" style="color:white">
           <b> <?= $key ?></b>
        </h3>
        <a class="btn btn-outline-success me-2 color-white" href="list.php?q=<?= $key    ?>" type="button">See More</a>
    </div>
  
    <div style="display:flex;  overflow-x:auto ">
    <?php foreach($value as $movie){ ?>
    <div class="card movie_card"  >
                <img src="<?= $movie['movie']['image_location']     ?>" class="card-img-top" alt="...">
                <div class="card-body" >                    
                    <h5 class="card-title"><?= $movie['movie']['movie_title'] ?></h5>
                    <span class="movie_info"><?= implode(',',$movie['genre']) ?></span>
                    <div class="w-100 d-flex justify-content-end">
                    <a class="btn btn-success "  href="item.php?q=<?= base64_encode($movie['movie']['id']) ?>" >View</a>
                    </div>
                </div>
            </div>
            <?php } ?>
         
    </div>

    <?php } ?>

<!--end of  Movie cards section of the page  -->
<script src="test.js"  ></script>
<?php include 'footer.php'  ?>