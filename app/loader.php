<?php
    $db_info = include_once 'Configs/config.php';    

    spl_autoload_register(function($inc){

        require 'Routers/'.$inc.'.class.php';
    });
    
    $db_conn = new Database($db_info);
    // $conn = $db->getConnect();
