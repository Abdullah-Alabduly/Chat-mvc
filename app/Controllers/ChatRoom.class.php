<?php
    session_start();

    class ChatRoom extends UserHelper{

        private static $chatRoomModel;

        public static function chat(){
            if(isset($_GET['user_id'])){
                self::$chatRoomModel = Controller::model('chatRoomModel');
                $user_id = preg_replace('/\D/', '', $_GET['user_id']);
                $userdata = self::$chatRoomModel->getUserData($user_id);
                Controller::view('chatroom', $userdata);
            }else echo "error";  
        }

        public static function send_msg(){
            self::$chatRoomModel = Controller::model('chatRoomModel');
            if(isset($_SESSION['unique_id'])){
                $chatdata = [];
                self::fetchData($_POST, $chatdata);
                self::cleanInputs($chatdata);
                $isEmpty = self::checkEmptyInputs($chatdata);

                if(!$isEmpty){
                    self::$chatRoomModel->insert_msg($chatdata);
                }
            }else{
                header("location: login");
            }
        }
        //////////////////////////////////////
        public static function getMsgs(){
            if(isset($_SESSION['unique_id'])){
            self::$chatRoomModel = Controller::model('chatRoomModel');
            $chatdata = [];
            self::fetchData($_POST, $chatdata);
            self::cleanInputs($chatdata);
            $output = "";
            $rowchat = self::$chatRoomModel->getMsgs($chatdata['msg_sender_id'], $chatdata['msg_resciver_id']);
            $output = self::printMsgs($rowchat);
            echo $output;
            }else{
                header("location: login");
            }

        }

        public static function printMsgs($msgsdata){
            $output="";
            if(!empty($msgsdata)){
                foreach($msgsdata as $msg){
                    if($msg['msg_sender_id'] == $_SESSION['unique_id']){ // sender message
                    $output .='<div class="chat outgoing">
                                    <div class="details">
                                        <p>'.$msg['msg'].'</p>
                                    </div>
                                </div>';
                    }else{ // resciver message
                        $output .= '<div class="chat incoming">
                                        <img src = "../images/'.$msg['img'].'">
                                        <div class="details">
                                            <p>'.$msg['msg'].'</p>
                                        </div>
                                    </div> ';
                    }
                }
            }
            return $output;
        }
    }