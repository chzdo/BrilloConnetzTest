<?php include('header.php')  ?>

<!-- Menu section of the page -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<!--end of  Menu section of the page -->

<hr>

<!-- including alert page for messages -->
<?php include '../alert.php'  ?>


<!-- form for editing name of customer  -->
<form class="form-signin" style="width:33rem" action="../controllers/customers.php" method="post">
    <h1 class="h3 mb-3 font-weight-normal">Edit Name</h1>

    <label class="sr-only">First Name</label>
    <input type="txt" id="inputEmail" class="form-control" name="first_name" value="<?= $customer_info['first_name'] ?>" placeholder="First Name" required>
    <label class="sr-only">Last Name</label>
    <input type="text" id="inputEmail" class="form-control" name="last_name" value="<?= $customer_info['last_name'] ?>" placeholder="Last Name" required>
    <label class="sr-only">Other Name</label>
    <input type="text" id="inputEmail" class="form-control" name="other_name" value="<?= $customer_info['other_name'] ?>" placeholder="Other Name" required>
    <input hidden name="update_name" />
    <button class="btn btn-success " type="submit">Save</button>

</form>
<!--  end ofform for editing name of customer  -->

<hr />

<!-- form for changing phone number and other details  -->
<form class="form-signin" action="../controllers/customers.php" method="post">
    <h1 class="h3 mb-3 font-weight-normal">Edit Other Information</h1>
    <label for="inputEmail" class="sr-only">Phone Number</label>
    <input type="text" name="phone_number" class="form-control" placeholder="Phone Number" value="<?= $customer_info['phone_number'] ?>" required>
    <label for="inputEmail" class="sr-only">Date of Birth</label>
    <input type="date" name="dob" class="form-control" value="<?= $customer_info['dob'] ?>" required autofocus>
    <label for="inputEmail" class="sr-only">Address</label>
    <textarea name="address" class="form-control" placeholder="Address" required><?= $customer_info['address'] ?></textarea>
    <input hidden name="add_info" />
    <button class="btn  btn-primary " type="submit">save</button>

</form>
<!--  end of form for changing phone number and other details  -->


<hr />

<!-- form for changing password   -->
<form class="form-signin" action="../controllers/customers.php" method="post">
    <h1 class="h3 mb-3 font-weight-normal">Change Password</h1>
    <label class="sr-only">Old Password</label>
    <input type="password" class="form-control" placeholder="Old Password" name="oldpassword" required>
    <label class="sr-only">New Password</label>
    <input type="password" name="newpassword" class="form-control" placeholder="New Password" required autofocus>
    <label class="sr-only">Re Password</label>
    <input type="password" name="renewpassword" class="form-control" placeholder="Re Password" required />
    <input hidden name="changePassword" />
    <button class="btn  btn-primary " type="submit">save</button>

</form>
<!-- end form for changing passwords  -->

</main>









<?php include('footer.php')  ?>