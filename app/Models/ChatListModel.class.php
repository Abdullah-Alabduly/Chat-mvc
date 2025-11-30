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
    }