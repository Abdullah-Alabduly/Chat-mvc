<?php 
    session_start();
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
        public static function chatlist(){
            $output="";
            self::$chatModel = Controller::model('ChatListModel');
                $chats = self::$chatModel->getChatslist($_SESSION['unique_id']);
                self::showChats($chats, $output);
            echo $output;
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
                    ($chat['status'] == 1)? $status = "🟢": $status = "🔘";
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