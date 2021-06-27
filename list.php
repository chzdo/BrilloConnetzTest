<?php

use Models\movies;

include 'header.php';


$movies = new movies();  //movie object


//check if param exist with genre category
if(!isset($_GET['q'])){

    $_SESSION['msg'] = ['code'=>404,"message"=>"Cannot find movie"];
    include 'alert.php';
    return;
 }
 


 $movie = $movies->getAll("",'genre',"",$_GET['q']); //get movies based on the genre category

 //if not movie return error
 if(count($movie) == 0){
    $_SESSION['msg'] = ['code'=>404,"message"=>"Cannot find movie"];
    include 'alert.php';
    return;
 }


?>
   


   <!-- movie card section -->
 
  <div class="row justify-content-center align-items-center">
  <?php foreach($movie as $movies){ ?>
  <div class="card movie_card col-1"  >
     
                <img src="<?=   $movies['image_location']      ?>" class="card-img-top" alt="...">
                <div class="card-body" >                    
                    <h5 class="card-title"><?=   $movies['movie_title']       ?></h5>
                    <h6 class="card-title"><?=   number_format(((int)$movies['size']/(1000*1000)),2)."MB"       ?></h6>
                     <div class="w-100 d-flex justify-content-end">
                    <a class="btn btn-success " href="item.php?q=<?= base64_encode($movies['id']) ?>" >View</a>
                    </div>
                </div>
            </div>

            <?php } ?>
      

  </div>
   
  <!-- end of movie card section -->
 

 

 
  <?php include 'footer.php'  ?>