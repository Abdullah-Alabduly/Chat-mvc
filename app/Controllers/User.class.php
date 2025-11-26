<?php
    session_start();
    class User{
       


        public static function index(){

            Controller::view('login');
        }
        
         public static function login(){
           $userModel = Controller::model('UserModel');
           $data = $userModel::getdata();

           Controller::view('login',$data);
        }

        public static function register(){
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                echo 'it works';
            }
            Controller::view('register');
        }
        public static function reg(){
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                echo 'Success';
            }
        }
    }