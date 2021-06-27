<?php 
include('header.php');



$purchase = $customer->getPurchases();  //get purchases by user

if (!$purchase) {
  $purchase = [];
}

?>


<!-- menu section of page -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Dashboard</h1>

</div>
<!-- end of menu section of page -->
<hr>


<h2>Here are your previous purchases</h2>

<hr>

<!-- table  section of page -->
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th>#</th>
        <th>Movie Title</th>
        <th>Amount</th>
        <th>Payment Ref</th>
        <th>Date Purchased</th>
        <th>Action </th>
      </tr>
    </thead>
    <tbody>
      <?php
      $i = 1;
      foreach (@$purchase as $movie) { ?>

        <tr>
          <td><?= $i++ ?></td>
          <td><?= $movie['name'] ?></td>
          <td><?= $movie['amount'] ?></td>
          <td><?= $movie['payment_ref'] ?></td>
          <td><?= $movie['payment_date'] ?></td>
          <td>
            <div class="btn-group mr-2">
              <a class="btn btn-success" href="../item.php?q=<?= base64_encode($movie['id']) ?>">
                View
              </a>
              <a class="btn btn-danger" href="../<?= $movie['file_location'] ?>">
                Download
              </a>
            </div>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<!-- end of table  section of page -->
</main>






<?php include('footer.php')  ?>