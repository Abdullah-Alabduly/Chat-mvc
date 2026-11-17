<?php

    class chatRoomModel {
        private  $conn;
        public function __construct()
        {
            $this->conn = $GLOBALS['db_conn'];
        }
        public  function getUserdata($userId){
            $SQL = "SELECT * FROM users WHERE unique_id = :userId";
           $this->conn->query($SQL); 
           $this->conn->bind(':userId', $userId);  
           
           return $this->conn->fetch(); 
        }
        // duplicated
        public function insert_msg($data){
            if(empty($data))return false;
            
            $columns = array_keys($data);
            $placeholders = array_map(fn($col)=>':'.$col, $columns);

            $SQL = "INSERT INTO messages (" . implode(',', $columns).")
                     VALUES (" . implode(',' , $placeholders).")";
            $this->conn->query($SQL);

            foreach ($data as $col => $val) {
               $this->conn->bind(':'.$col, $val);
            }
             $this->conn->execute();
             return $this->conn->rowCount();
        }

        // [Real-time] Cheap fingerprint of the chat list state (new message,
        // new user, online status change). The stream compares it with the
        // previous value and only renders HTML when something really changed.
        public function listRevision(){
            $SQL = "SELECT
                        (SELECT COALESCE(MAX(msg_id),0) FROM messages) AS last_msg,
                        (SELECT COUNT(*) FROM users) AS total_users,
                        (SELECT COALESCE(SUM(status),0) FROM users) AS online_users";
            $this->conn->query($SQL);
            $row = $this->conn->fetch();
            return implode(':', array_map('strval', $row));
        }

        // [Real-time] Same idea for one conversation: number of messages plus the
        // newest message id of the two users.
        public function pairRevision($sender_id, $resciver_id){
            $SQL = "SELECT COALESCE(MAX(msg_id),0) AS last_msg, COUNT(*) AS total
                    FROM messages
                    WHERE (msg_sender_id = :sender AND msg_resciver_id = :resciver)
                       OR (msg_sender_id = :resciver AND msg_resciver_id = :sender)";
            $this->conn->query($SQL);
            $this->conn->bind(':sender', (int)$sender_id);
            $this->conn->bind(':resciver', (int)$resciver_id);
            $row = $this->conn->fetch();
            return implode(':', array_map('strval', $row));
        }

        public function getMsgs($sender_id, $resciver_id){
            $SQL = "SELECT * FROM messages 
                LEFT JOIN users ON users.unique_id = messages.msg_sender_id
                WHERE (msg_sender_id = :sender AND msg_resciver_id = :resciver)
                OR (msg_sender_id = :resciver AND msg_resciver_id = :sender) ORDER BY msg_id ASC";
            $this->conn->query($SQL); 
            $this->conn->bind(':sender', $sender_id);
            $this->conn->bind(':resciver', $resciver_id);
            return $this->conn->fetchAll();
                   
        }
    }

    