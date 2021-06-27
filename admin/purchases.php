<?php

use Models\movies;  //movies class

include('header.php');  //including the header file

$movies = new movies(); //creating an instance of movie class
$list = [];

//setting up params for sorting and filtering
$sort_type = "DESC";
$sort = "id";
$filter = "";
$filter_type = "";
$filter_value = "";


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
 *   * Checking if the filter params is set to activate filtering
 */
if (isset($_GET['filter'])) {
  $filter = $_GET['filter'];
}

/**
 * @param q
 *   * Checking for a customer ID to filter purchases made by that customer
 */

if (isset($_GET['q'])) {
  $filter_value = base64_decode($_GET['q']);
}

//calling the getAllpurchases function of the movie class
$list = $movies->getAllPurchases($sort, $filter, $sort_type, $filter_value);


?>

<!-- Start of the menu section of the page -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Dashboard</h1>
  <div class="btn-toolbar mb-2 mb-md-0">

    <div class="dropdown">
      <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        filter & sort
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <li><a class="dropdown-item" href="purchases.php">All</a></li>
        <li>
          <form>
            <span>
              Monthly Sales
            </span>
            <input class="form-control" type="month" name="filter" onchange="" />
            <button class="btn btn-outline-primary">Filter </button>
          </form>

        </li>
      </ul>
    </div>
  </div>
</div>

<!-- Start of the menu section of the page -->


<!-- Start of the purchase count and amount   -->
<h2>
  <?php
  if (isset($_GET['filter'])) {
    echo "Here is a summary for " . $_GET['filter'];
  } else {
    echo "Here is a summary of all purchases made";
  }
  ?>
</h2>

<span>
  Total : <span class="badge bg-success">
    <?= count($list) ?>
  </span>
</span>
<span>
  Total Amount: <span class="badge bg-success">
    <?= array_sum(array_column($list, 'payment_amount')) ?>
  </span>
</span>
<!-- End of the purchase count and amount   -->


<!-- Start of purchase table   -->
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th>#</th>
        <th>Customer Name</th>
        <th>Cover</th>
        <th>Movie Title</th>
        <th>Amount</th>
        <th>Payment Ref</th>
        <th>Date Purchased</th>

      </tr>
    </thead>
    <tbody>
      <?php
      $i = 1;
      foreach ($list as $purchase) {
      ?>
        <tr>
          <td> <?= $i++ ?></td>
          <td><?= $purchase['name']        ?></td>
          <td> <img src="../<?= $purchase['image_location'] ?>" width=50 height=50 /> </td>
          <td><?=   $purchase['movie_title']        ?></td>
          <td><?=   $purchase['payment_amount']        ?></td>
          <td><?=   $purchase['payment_ref']        ?></td>
          <td><?=   $purchase['payment_date']        ?></td>


        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<!-- End of purchase table   -->
</main>









<?php include('footer.php')  ?>