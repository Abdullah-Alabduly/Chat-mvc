<?php
    class UserModel{
        private static $conn;

        public function __construct()
        {
            self::$conn = $GLOBALS['db_conn'];            
        }

        public static function getdata(){
            $query = self::$conn->query("SELECT * FROM users");
            
            $data = "hello";
            
            return $data;
        }
    }