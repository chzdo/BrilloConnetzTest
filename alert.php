

<?php
if(isset($_SESSION['msg'])){
   ?>
<div class="alert  <?= $_SESSION['msg']['code'] > 300 ? 'alert-danger' :'alert-success'?>" role="alert">
<?= $_SESSION['msg']['message'] 

   
?>
</div>

<?php

unset($_SESSION['msg']);
}


?>