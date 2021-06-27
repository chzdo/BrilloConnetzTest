<?php
spl_autoload_register('AutoLoader');

function AutoLoader($className)
{
    $file = str_replace('\\',DIRECTORY_SEPARATOR,$className);

    require_once  $file . '.php'; 
    //Make your own path, Might need to use Magics like ___DIR___
}

require('config.php');
session_start();
extract(Tables);