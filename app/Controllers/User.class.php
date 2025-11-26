<?php
    session_start();
    class User{
       


        public static function index(){
        }
        
         public static function login(){
           $userModel = Controller::model('UserModel');
           $data = $userModel::getdata();

           Controller::view('404',$data);
        }
    }