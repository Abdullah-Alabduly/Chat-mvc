<?php
    $db_info = include_once 'Configs/config.php';    
    require_once 'app/helpers/UserHelper.class.php';
  
    // [SQLite migration] The autoloader relies on the class name matching the
    // file name byte for byte. This only worked on Windows (case-insensitive
    // filesystem) because the router file was named "router.class.php"; it was
    // renamed to "Router.class.php" so the app also runs on Linux/macOS.
    spl_autoload_register(function($inc){
        require 'Routers/'.$inc.'.class.php';
    });
    
    $db_conn = new Database($db_info);
