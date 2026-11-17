<?php 
    // [Real-time] Only start the session if none is active yet, so this file can
    // be autoloaded from inside the long-lived stream without a PHP notice.
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    class ChatList extends UserHelper{
        private static $chatModel;
         public static function profile(){
            if(isset($_SESSION['unique_id'])){
                self::$chatModel = Controller::model('ChatListModel');
                $profile = self::$chatModel->getUserData($_SESSION['unique_id']);
               Controller::view('chatlist',$profile);
            }else{
                header("location: login");
            }
    
        }

        // [Real-time] The chat list HTML is now produced by chatsHtml() so the
        // real-time stream and this endpoint always return identical markup.
        public static function chatlist(){
            if(!isset($_SESSION['unique_id'])) return;
            echo self::chatsHtml($_SESSION['unique_id']);
        }

        // [Real-time] Returns the rendered chat list as a string instead of
        // echoing it, which is what the SSE stream needed.
        public static function chatsHtml($userId){
            $output = "";
            self::$chatModel = Controller::model('ChatListModel');
            $chats = self::$chatModel->getChatslist($userId);
            self::showChats($chats, $output);
            return $output;
        }

        

        public static function search(){
            $output = "";
            if(isset($_POST['searchTerm'])){
                $searchTerm = self::filterValue($_POST['searchTerm']);
                self::$chatModel = Controller::model('ChatListModel');
                $chats = self::$chatModel->findByName($searchTerm, $_SESSION['unique_id']);
                
                self::showChats($chats , $output);
                echo $output;
            }
        }

        // helper func
        public static function showChats($chats, &$output){
            if(!empty($chats)){
                foreach ($chats as $chat) {
                    ($chat['status'] == 1)? $status = "🟢": $status = "";
                     $output .='<a href="chat?user_id='.$chat['unique_id'].'">
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
            }else $output .="No User Found";
            
        } 

    }
