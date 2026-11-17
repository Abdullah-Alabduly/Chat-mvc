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
            // [SQLite migration] PDO SQLite always returns 0 from rowCount()
            // on a SELECT, which made the "email already exists" check useless.
            // The real number of rows is counted from the fetched result.
            return count($this->conn->fetchAll());
        }
        // duplicated
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
        // [Password hashing] Passwords are stored as password_hash() output, so the
        // password can no longer be part of the SQL query: the row is loaded by
        // email and the hash is checked with password_verify().
        public function getUserlog($email, $password){
            $SQL = "SELECT * FROM users WHERE email = :userEmail";
            $this->conn->query($SQL);
            $this->conn->bind(':userEmail', $email);
            $rowUser = $this->conn->fetch();

            if(empty($rowUser)) return [];

            $stored = (string)$rowUser['password'];

            if(password_verify((string)$password, $stored)){
                return $rowUser;
            }

            // [Password hashing] Legacy support: accounts created before hashing
            // was added still contain the plain text password. They are accepted
            // once and the row is upgraded to a real hash right away, so the old
            // value never stays in the database after the next login.
            if(!self::isHash($stored) && hash_equals($stored, (string)$password)){
                $this->updatePassword($rowUser['unique_id'], password_hash((string)$password, PASSWORD_DEFAULT));
                return $rowUser;
            }

            return [];
        }

        // [Password hashing] Tells a password_hash() value apart from the plain
        // text password of an old account.
        private static function isHash($value){
            return is_string($value) && password_get_info($value)['algoName'] !== 'unknown';
        }

        // [Password hashing] Stores a new hash for one user (used by the legacy
        // upgrade above).
        public function updatePassword($userId, $hash){
            $SQL = "UPDATE users SET password = :hash WHERE unique_id = :userId";
            $this->conn->query($SQL);
            $this->conn->bind(':hash', $hash);
            $this->conn->bind(':userId', (int)$userId);
            $this->conn->execute();
        }

        public function updateStatus($userId, $status){
            $SQL ="UPDATE users SET status =:status WHERE unique_id =:userId";
            $this->conn->query($SQL);
            $this->conn->bind(":status", $status);
            $this->conn->bind(":userId", $userId);
            $this->conn->execute();
        }
        public function close(){
            $this->conn=null;
        }
    }