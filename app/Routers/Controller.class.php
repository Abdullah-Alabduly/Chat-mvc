<?php

    class Controller
    {
        public static function model($model)
        {
            if(file_exists('app/Models/' .$model.'.class.php'))
            {
                require_once 'app/Models/' .$model.'.class.php';
                return new $model;
            }
        }

        public static  function view($view, $data = [])
        {
            if(file_exists('app/Views/' .$view.'.view.php'))
            {
                require_once 'app/Views/' .$view.'.view.php';
            }
            else
            {
                die('This view not Exist');
            }
        }
    }