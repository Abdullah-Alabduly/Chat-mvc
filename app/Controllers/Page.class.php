<?php

    // [Visual review] Only start the session if none is active yet, so this
    // controller can tell a logged-in visitor from a guest.
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    class Page{

        // [Visual review] The home route used to echo "Page not found" and then
        // send a redirect header, which cannot work because the output was
        // already sent, so visitors got a raw PHP warning page. It now sends one
        // real redirect: members go to their chat list, guests to the login form.
        public static function index(){
            if(isset($_SESSION['unique_id'])){
                header("Location: profile");
            }else{
                header("Location: login");
            }
            exit;
        }
    }
