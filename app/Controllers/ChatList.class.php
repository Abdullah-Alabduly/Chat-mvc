<?php 
    session_start();
    class ChatList{
        private static $chatModel;
         public static function profile(){
             self::$chatModel = Controller::model('ChatListModel');
             $profile = self::$chatModel->getUserData($_SESSION['unique_id']);
            Controller::view('chatlist',$profile);
    
        }
        public static function chatlist(){
            $output="";
            self::$chatModel = Controller::model('ChatListModel');
                $chats = self::$chatModel->getChatslist($_SESSION['unique_id']);
                foreach ($chats as $chat) {
                    ($chat['status'] == 1)? $status = "🟢": $status = "🔘";
                     $output .='<a href="chat.php?user_id='.$chat['unique_id'].'">
                                <div class="content">
                                    <img src="app/images/'.$chat['img'].'" alt="">
                                    <div class="details">
                                       <span>'.$chat['fname']." ".$chat['lname'].'</span>
                                            <p>'.self::$chatModel->getLastMessage($_SESSION['unique_id'], $chat['unique_id']).'</p>
                                    </div>
                                     </div>
                                 <div class="status-dot"><i class="circle">'.$status.'</i></div>
                                </a>';  
                }
            echo $output;
        }

    }