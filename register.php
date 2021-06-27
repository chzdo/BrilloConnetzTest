<?php


include 'header.php'


?>



<?php include 'alert.php' ?>

<!-- form section -->
<form class="form-signin" method="post" action="controllers/customers.php">
    
      <h1 class="h3 mb-3 font-weight-normal">Please Sign Up</h1>
      <label for="inputEmail" class="sr-only">Email address</label>
      <input type="email" id="inputEmail" class="form-control" placeholder="Email address" name="email" required autofocus>
      <label  class="sr-only">Phone Number</label>
      <input type="text" id="inputEmail" class="form-control" placeholder="Phone Number" name="phone_number" required >
      <label class="sr-only">First Name</label>
      <input type="text" id="inputEmail" class="form-control" placeholder="First Name" name="first_name" required >
      <label  class="sr-only">Last Name</label>
      <input type="text" id="inputEmail" class="form-control" placeholder="Last Name" name="last_name" required >
      <label  class="sr-only">Password</label>
      <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="password" required>
      <input hidden name="register" />
       <div class="d-flex justify-content-end w-100">
           <a href="login.php" class="btn btn-outline-warning">
               Sign In
           </a>
       </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign Up</button>
  
    </form>

<!-- end of form section -->


    </div>
    <?php include 'footer.php'  ?>