
<?php

include 'header.php';



?>
    <div class="d-flex flex-direction-column justify-content-center align-items-center w-100  bg-secondary " style="height:100vh">
   

<!-- form section -->
<form class="form-signin" method="post" action="controllers/customers.php">
<?php include 'alert.php'; ?>
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
      <input hidden name="redirect_url" value="<?=     @$_GET['redirect_uri']         ?>"  />
      <label for="inputEmail" class="sr-only">Email address</label>
      <input type="email" id="inputEmail" class="form-control" placeholder="Email address" name="email" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Password" required>
      <input name="login"  hidden />
       <div class="d-flex justify-content-end w-100">
           <a href="register.php" class="btn btn-outline-warning">
               Sign up
           </a>
       </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
  
    </form>
<!-- end of  form section -->


    </div>
    <?php include 'footer.php'  ?>