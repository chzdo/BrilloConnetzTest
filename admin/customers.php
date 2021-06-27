<?php

use Models\Customer;  //Customer Model

include('header.php'); //Including Header File


$customers = new Customer(); //Initializing customer class
$list = [];

/**
 * Below are variables used for sorting and filtering the table
 */
$sort_type = "DESC";
$sort = "id";
$filter = "";
$filter_type = "";
$filter_value = "";

/**
 * End of Initialization of sorting and filtering variables
 */


/*
  * @params sort
  * Cheching if the  sort paramater is set then
     set the sort type (ASC or DESC)
     and the sort => column to be sorted
  */
if (isset($_GET['sort'])) {

  $sort_type = isset($_GET['sort_type']) ? $_GET['sort_type'] : "DESC";
  $sort  = $_GET['sort'];
}

/*
  * @params filter
  * Cheching if the  filter paramater is set then
     set the filter value and filter
    filter is column to be filtered.
  */

if (isset($_GET['filter']) && isset($_GET['filter_value'])) {

  $filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : "1";
  $filter  = $_GET['filter'];
  $filter_value = $_GET['filter_value'];
}


//Calling the getAll function of the customer object
$list = $customers->getAll($sort, $filter, $sort_type, $filter_type, $filter_value);

?>

<?php

/**
 * End of Menu Options
 */

 ?>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Dashboard</h1>
  <div class="btn-toolbar mb-2 mb-md-0">

    <div class="dropdown">
      <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        filter & sort
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <li><a class="dropdown-item" href="customers.php">All</a></li>
        <li><a class="dropdown-item" href="customers.php?sort=email&sort_type=ASC">Sort by Email Ascending</a></li>
        <li><a class="dropdown-item" href="customers.php?sort=email&sort_type=DESC">Sort by Email Descending</a></li>
        <li><a class="dropdown-item" href="customers.php?sort=dob&sort_type=ASC">Sort by DOB ASC</a></li>
        <li><a class="dropdown-item" href="customers.php?sort=dob&sort_type=DESC">Sort by DOB DESC</a></li>
        <li><a class="dropdown-item" href="customers.php?filter=age&filter_type=1&filter_value=50">Filter Customers above 50 years</a></li>
        <li><a class="dropdown-item" href="customers.php?filter=age&filter_type=0&filter_value=50">Filter Customers below 50 years</a></li>
      </ul>
    </div>
  </div>
</div>

<?php
/**
 * End of Menu Options
 */



/**
 * Below is the table for Customers
 */

?>

<h2>Hi Admin, Here are your customers</h2>

<span>
  Total : <span class="badge bg-success">
    <?= count($list) ?>
  </span>
</span>
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th>#</th>
        <th>Customer Name</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Address</th>
        <th>Date of Birth</th>
        <th>Actions</th>

      </tr>
    </thead>
    <tbody>
      <?php
      $i = 1;
      foreach ($list as $item) { ?>
        <tr>
          <td> <?= $i++ ?></td>
          <td><?= @$item['name'] ?></td>
          <td><?= @$item['email'] ?></td>
          <td><?= @$item['phone_number'] ?></td>
          <td><?= @$item['address'] ?></td>
          <td><?= @$item['dob'] ?></td>
          <td>
            <div class="btn-group mr-2">
              <a class="btn btn-success" href="purchases.php?q=<?= base64_encode(@$item['id'])  ?>">
                View Purchases
              </a>

            </div>
          </td>

        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
</main>







<!-- 

Including the footer file 

-->

<?php include('footer.php')  ?>