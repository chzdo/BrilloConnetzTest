<?php

use Models\movies;

include 'header.php'; //include header file

$movies = new movies(); //movie object


//check if params exist with movie ID
if (!isset($_GET['q'])) {

  $_SESSION['msg'] = ['code' => 404, "message" => "Cannot find movie"];
  include 'alert.php';
  return;
}


//find is movie exist
if (!($movies->find(base64_decode($_GET['q'])))) {

  $_SESSION['msg'] = ['code' => 404, "message" => "Cannot find movie"];
  include 'alert.php';
  return;
}

$movie = $movies->get(); //get movie details


include 'alert.php'; //include alert message page

?>
<!-- Movie Conatiner -->
<div class="row m-5">

  <div class="col-6">
    <img style="position:relative; width:100%; height:500px; " src="   <?= $movie[0]['image_location'] ?>" />

  </div>
  <div class="col-6">
    <h1 class="movie_title"><b>
        <?= $movie[0]['name'] ?>
      </b></h1>
    <span class="movie_genre">
      <?= implode(",", array_values(array_column($movies->getGenreName(), 'name'))) ?>
    </span>

    <h6 class="h6 mt-5">
      <b>
        Story
      </b>
    </h6>
    <p class="movie_desc">
      <?= $movie[0]['description'] ?>
    </p>


    <span class="movie_size ">
      <?= number_format(((int)$movie[0]['size'] / (1000 * 1000)), 2) . "MB"  ?>
    </span>

    <?php

    if (isset($_SESSION['user']) && $_SESSION['user']->checkPurchase($movie[0]['id'])) { ?>

      <a href="<?= $movie[0]['file_location'] ?>" class="btn btn-success mt-5 w-100">Download<span>

        </span> </a>
    <?php } else {



    ?>
    
      <a class="btn btn-success mt-5 w-100" href="<?= $_SERVER['REQUEST_URI'] ?>&pay">
      Buy <span>
          <?= $movie[0]['amount'] ?>
        </span>
    </a>

    <?php } ?>


  </div>

</div>

<!--end of  Movie Conatiner -->






<?php

if (isset($_GET['pay'])) {
  if (!isset($_SESSION['user'])) {

    header('location: login.php?redirect_uri=' . $_SERVER['REQUEST_URI']);
    return;
  }
  $pay = $_SESSION['user']->getCardDetails();

  if ($pay == null) {
    header('location:  dashboard/');
    return;
  }
?>


<div class="modal2 ">
    
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Payment</h5>
          <a  type="button" class="close bg-danger" href="item.php?q=<?= $_GET['q'] ?>" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
</a>
        </div>
        <div class="modal-body">
           <div class="d-flex justify-content-cent align-item-center">
            <span>
              Pay with this card :
            </span>**** **** **** <?= $pay['last4'] ?>
          </div>
          <form method="post" action="controllers/payment.php">
            <input hidden name="pay" />
            <input hidden name="amount" value="<?= $movie[0]['amount'] ?>" />
            <input hidden name="movie_id" value="<?= $movie[0]['id'] ?>" />
            <button class="btn btn-success mt-5 w-100">Pay<span>
                <?= $movie[0]['amount'] ?>
              </span> </Button>
          </form>
        </div>
        
    
    </div>





<?php } ?>





<?php

include 'footer.php' ;

?>