<?php
    class ChatListModel{
        private  $conn;
        public function __construct()
        {
            $this->conn = $GLOBALS['db_conn'];
        }
        public  function getUserdata(int $userId){
            $SQL = "SELECT * FROM users WHERE unique_id = :userId";
           $this->conn->query($SQL); 
           $this->conn->bind(':userId', $userId);  
           
           return $this->conn->fetch(); 
        }

        public function getChatsList($userId){
            $SQL = "SELECT * FROM users WHERE NOT unique_id = :userId";
            $this->conn->query($SQL); 
            $this->conn->bind(':userId', $userId);
            return $this->conn->fetchAll();
                   
        }

        public function getLastMessage($userId, $rowUserId){
             $SQL = "SELECT * FROM messages 
                         WHERE (msg_sender_id =:rowId OR msg_resciver_id =:rowId)
                         AND (msg_sender_id =:userId OR msg_resciver_id = :userId) ORDER BY msg_id DESC LIMIT 1";
             $this->conn->query($SQL);
             $this->conn->bind(':rowId', $rowUserId);
             $this->conn->bind(':userId', $userId);
             $rowMes = $this->conn->fetch();
             (!empty($rowMes)) ? $res = $rowMes['msg'] : $res = "No message available";
             //  trimming msg 
                (strlen($res) > 25) ? $msg = substr($res, 0, 30).'...' : $msg = $res;
                //  adding YOU: befor the msg if you send it
                (isset($rowMes['msg_sender_id']) && $rowMes['msg_sender_id'] == $userId )? $sender = "YOU: ":$sender= "";

            return $sender . $msg;

        }

        public function findByName($name, $currentUser){
            $SQL = "SELECT * FROM users
                        WHERE NOT unique_id =:currentUser 
                        AND (fname LIKE '%{$name}%' OR lname LIKE '%{$name}%')";
           $this->conn->query($SQL); 
           $this->conn->bind(':currentUser', $currentUser);              
           return $this->conn->fetchAll(); 
        }


    }
    