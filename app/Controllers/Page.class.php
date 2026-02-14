<?php


class Page{

    public static function index(){
        echo "Page not found";
        if(!isset($_SESSION['unique_id'])){
        header("location: login");
    }
    }
}