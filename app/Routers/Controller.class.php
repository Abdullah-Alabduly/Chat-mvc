<?php

    class Controller
    {
        public function model($model)
        {
            if(file_exists('app/models/' .$model.'.class.php'))
            {
                require_once 'app/Models/' .$model.'.class.php';
                return new $model;
            }
        }

        public function view($view, $data = [])
        {
            if(file_exists('app/Views/' .$view.'.php'))
            {
                require_once 'app/Views/' .$view.'.php';
            }
            else
            {
                die('This view not Exist');
            }
        }
    }