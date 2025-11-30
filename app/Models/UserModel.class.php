<?php
    class UserModel{
        private  $conn;

        public function __construct()
        {
            $this->conn = $GLOBALS['db_conn'];            
        }

        public  function findByEmail($email){
            $SQL= "SELECT email FROM users WHERE email = :userEmail";
            $this->conn->query($SQL);
            $this->conn->bind(':userEmail', $email);
            $this->conn->execute();
            return $this->conn->rowCount();
        }

        public function addNewUser($data){

            if(empty($data))return false;
            
            $columns = array_keys($data);
            $placeholders = array_map(fn($col)=>':'.$col, $columns);

            $SQL = "INSERT INTO users (" . implode(',', $columns).")
                     VALUES (" . implode(',' , $placeholders).")";
            $this->conn->query($SQL);

            foreach ($data as $col => $val) {
               $this->conn->bind(':'.$col, $val);
            }
             $this->conn->execute();
             return $this->conn->rowCount();
        }
        public function close(){
            $this->conn=null;
        }
    }