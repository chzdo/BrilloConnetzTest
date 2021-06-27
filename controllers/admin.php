
<?php

use migrations\database; //database namespace

require_once('../autoloaders.php');   //including the autoloaders

unset($_SESSION['msg']); //feedback 



/**
 * @param login
 */
if (isset($_POST['login'])) {
  
    /**
     * Check if Id exist in POST
     */
    if (isset($_POST['id']) && !empty($_POST['id'])) {

        $db = new database();   

        if ($db->fetchOne(['id' => $_POST['id']], "select * from admin where id = ?")) { //Check if id exist

            $password = $db->result['password'];

            if (password_verify($_POST['password'], $password)) {  //Verify hash password
               
                $_SESSION['admin'] = $db->result['id'];
                header('location:  ../admin/');
                return;
            } else {
                $_SESSION['msg'] = ['code' => 400, "message" => "Incorrect Password"];
            }
        } else {
            $_SESSION['msg'] = ['code' => 404, "message" => "user not found"];
        }
    } else {
        $_SESSION['msg'] = ['code' => 400, "message" => "ID is required"];
    }

    header('location: ../admin/login.php');          //redirect to login if user doesnt exist

    return;
}












//header('location: ../error/badrequest.php');




?>