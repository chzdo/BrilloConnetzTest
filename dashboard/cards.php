<?php

include('header.php'); // including the header file


$card = $customer->getCardDetails(); //getting user card information


//if card not found show error
if ($card == NULL) {
    $_SESSION['msg'] = ['code' => 404, 'message' => "Cannot find card details"];
    include '../alert.php';
    return;
}




?>
<!-- adding link to card styling  -->
<link href="../static/cards.css" rel="stylesheet" />


 <!-- menu section of the page  -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>
<!-- end of  menu section of the page  -->



<h2>Here is the detail of your card</h2>


<!-- card section of the page  -->
<div class="card mt-5">
    <div class="bank-name" title="BestBank"><?= $customer_info['first_name'] . ' ' . $customer_info['last_name']  ?></div>
    <div class="chip">
        <div class="side left"></div>
        <div class="side right"></div>
        <div class="vertical top"></div>
        <div class="vertical bottom"></div>
    </div>
    <div class="data">
        <div class="pan" title="4123 4567 8910 1112">**** **** **** <?= $card['last4'] ?></div>

        <div class="exp-date-wrapper">
            <div class="left-label mt-5">EXPIRES END</div>
            <div class="exp-date mt-5">
                <div class="upper-labels">MONTH/YEAR</div>
                <div class="date" title="01/17"><?= $card['exp_month'] ?>/<?= $card['exp_year'] ?></div>
            </div>
        </div>

    </div>
    <div class="lines-down"></div>
    <div class="lines-up"></div>
</div>
<!-- end of card section of the page  -->



</main>









<?php include('footer.php')  ?>