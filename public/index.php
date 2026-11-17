<?php

    // [SQLite migration] Every request now starts from the project root so the
    // relative paths used across the app (app/Models, app/Views, app/images,
    // app/database) resolve exactly the same under Apache/XAMPP and under the
    // PHP built-in server.
    chdir(dirname(__DIR__));

    require_once 'app/loader.php';
    
    $rout = new Router();
