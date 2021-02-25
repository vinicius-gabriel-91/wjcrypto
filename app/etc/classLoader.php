<?php

spl_autoload_register(function($className){

    if (file_exists(__DIR__ . "/../model/$className.php")) {
        require_once(__DIR__ . "/../model/$className.php");
    } elseif (file_exists(__DIR__ . "/../etc/$className.php")){
        require_once(__DIR__ . "/../etc/$className.php");
    }
});


