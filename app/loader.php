<?php
    $db_info = include_once 'Configs/config.php';    
    require_once 'app/helpers/UserHelper.class.php';
  
    spl_autoload_register(function($inc){
        require 'Routers/'.$inc.'.class.php';
    });
    
    $db_conn = new Database($db_info);
