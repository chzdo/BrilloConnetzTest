<?php

use Models\movies;   //movies class

include('header.php'); //importing the header page

$movies = new movies();  //creating a new movies object
$list = [];

/**
 * setting up variables needed for soring and filtering
 */
$sort_type = "DESC";
$sort = "id";
$filter = "";
$filter_type = "";
$filter_value = "";

/**
 * End of setting up variables
 */



 /**
  * @param sort
  * Checking if the sort params is set to activate sorting
  */

if (isset($_GET['sort'])) {

  $sort_type = isset($_GET['sort_type']) ? $_GET['sort_type'] : "DESC";
  $sort  = $_GET['sort'];
}




 /**
  * @param filter
  * @param filter_value
  * Checking if the filter params is set to activate filtering
  */

if (isset($_GET['filter'])&&isset($_GET['filter_value'])) {

  $filter = $_GET['filter'];
  $filter_value = $_GET['filter_value'];
}


//Call the getAll function of movies with both sort and filter params
$list = $movies->getAll($sort, $filter, $sort_type,$filter_value);
$nlist = [];


//looping to re-arrange the movie list to have complete list of genre 
foreach ($list as $value) {

  $nlist[$value['movie_title']]['movie'] = $value;
  $nlist[$value['movie_title']]['genre'][] = $value['genre_name'];
}


?>

<!--
Menu section of the movies page
-->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Dashboard</h1>
  <div class="btn-toolbar mb-2 mb-md-0">

    <div class="dropdown">
      <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        filter & sort
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <li><a class="dropdown-item" href="movies.php">All</a></li>
        <li><a class="dropdown-item" href="movies.php?filter=genre&filter_value=action">Movies with genre Action</a></li>
    <li><a class="dropdown-item" href="movies.php?filter=name&filter_value=s">Movies ending with s</a></li>
      </ul>
    </div>
  </div>
</div>

<!--
 End of Menu section of the movies page
-->


<!-- Start of Movie count -->
<h2>
  <?php

  echo "Here is a List of Movies";

  ?>
</h2>

<span>
  Total : <span class="badge bg-success">
    <?= count($nlist) ?>
  </span>
</span>
<!-- End  of Movie count -->

<?php include('../alert.php'); //Including the page for showing for feedback messages  ?> 


<!-- Start of movie table -->
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th>#</th>

        <th>Cover</th>
        <th>Movie Title</th>
        <th>size</th>
        <th>amount</th>
        <th>Genre</th>
        <th>Date Created</th>
        <th>Action</th>

      </tr>
    </thead>
    <tbody>
      <?php
      $i = 1;
      foreach ($nlist as $purchase) { ?>
        <tr>
          <td> <?= $i++ ?></td>

          <td> <img src="../<?= $purchase['movie']['image_location'] ?>" width=50 height=50 /> </td>
          <td><?= $purchase['movie']['movie_title']        ?></td>
          <td><?= (number_format(((int)$purchase['movie']['size']/(1000 * 1000)) ,2))."MB"     ?></td>
          <td><?= $purchase['movie']['amount']        ?></td>
          <td><?= implode(",", $purchase['genre'])        ?></td>
          <td><?= $purchase['movie']['date_created']        ?></td>
          <td>
            <div class="btn-group mr-2">
              <a class="btn btn-success" href="upload.php?q=<?= base64_encode(@ $purchase['movie']['id'])  ?>">
                Edit
              </a>
              <form method="post" action="../controllers/movies.php">
                <input hidden name="id" value="<?= $purchase['movie']['id']   ?>" />
                <input hidden name="delete" value="<?= $purchase['movie']['id']   ?>" />
                <button class="btn btn-danger" href="purchases.php?q=<?= base64_encode(@$item['id'])  ?>">
                  Delete
                </button>
              </form>

              <a class="btn btn-primary" href="purchases.php?q=<?= base64_encode(@$item['id'])  ?>">
                View
              </a>
            </div>
          </td>

        </tr>
      <?php } ?>
    </tbody>
  </table>

  <!-- End of movie table -->
</div>
</main>









<?php include('footer.php')  ?>