<?php
    // [Local server] Apache uses the .htaccess rewrite rules to forward every
    // request to public/index.php. The PHP built-in server does not read
    // .htaccess, so this router script reproduces the same behaviour: real
    // files (css, js, images) are served as they are, everything else goes to
    // the front controller. Usage: php -S 0.0.0.0:8080 router.php
    $root = __DIR__;
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = realpath($root.$path);

    if($file !== false && is_file($file) && strpos($file, $root) === 0){
        return false; // let the built-in server serve the static file
    }

    require $root.'/public/index.php';
