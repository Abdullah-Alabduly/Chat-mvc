<?php 
    session_start();
    class ChatList{
        private static $userModel;
        public static function chatlist(){
            self::$userModel = Controller::model('ChatListModel');
             $profile = self::$userModel->getUserData($_SESSION['unique_id']);
            Controller::view('chatlist', $profile);
        }
    }