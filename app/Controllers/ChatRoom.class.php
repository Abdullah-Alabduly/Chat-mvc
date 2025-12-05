<?php
    session_start();

    class ChatRoom{
        private static $chatRoomModel;
        public static function chat(){
            if(isset($_GET['user_id'])){
                self::$chatRoomModel = Controller::model('chatRoomModel');
                $user_id = preg_replace('/\D/', '', $_GET['user_id']);
                $userdata = self::$chatRoomModel->getUserData($user_id);
                Controller::view('chatroom', $userdata);
            }else echo "error";
            
            
        }
    }