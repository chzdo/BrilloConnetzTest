<?php
require 'autoloaders.php';

unset($_SESSION['user']);
unset($_SESSION['admin']);
unset($_SESSION['msg']);
session_destroy();
header('location: index.php');


?>