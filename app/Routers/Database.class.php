<?php

    // DB Conecction
    class Database{
        private $pdo;
        private $stmt;

        public function __construct($db_info)
        {
            // [SQLite migration] The old code glued host/port/dbname into a
            // "mysql:" DSN. SQLite only needs the path of the database file,
            // so the DSN is now "sqlite:/absolute/path/to/chat.sqlite".
            $path = $db_info['path'];
            $dir  = dirname($path);
            if(!is_dir($dir)){
                // [SQLite migration] Make sure app/database/ exists before the
                // driver tries to create the file.
                mkdir($dir, 0775, true);
            }
            $dsn = 'sqlite:'.$path;

            try {
                $this->pdo = new PDO($dsn);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);

                // [SQLite migration] WAL mode lets the real-time stream keep
                // reading while another request is inserting a message, and
                // busy_timeout waits for a lock instead of failing with
                // "database is locked" when two users write at the same time.
                $this->pdo->exec('PRAGMA journal_mode = WAL');
                $this->pdo->exec('PRAGMA busy_timeout = 5000');
                $this->pdo->exec('PRAGMA foreign_keys = ON');

                // [SQLite migration] Tables are created on demand, this replaces
                // the old chat.sql dump that had to be imported into MySQL.
                $this->createSchema();
            } catch (PDOException $e) {
                die("There is an issue: ".$e->getMessage());
            }

        }

        // [SQLite migration] Schema of the two tables used by the app. The
        // columns and names are identical to the old MySQL structure so the
        // models did not have to be rewritten.
        private function createSchema(){
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS users (
                unique_id INTEGER PRIMARY KEY,
                fname     TEXT    NOT NULL,
                lname     TEXT    NOT NULL,
                email     TEXT    NOT NULL UNIQUE,
                password  TEXT    NOT NULL,
                img       TEXT,
                status    INTEGER NOT NULL DEFAULT 0
            )");

            $this->pdo->exec("CREATE TABLE IF NOT EXISTS messages (
                msg_id          INTEGER PRIMARY KEY AUTOINCREMENT,
                msg_sender_id   INTEGER NOT NULL,
                msg_resciver_id INTEGER NOT NULL,
                msg             TEXT    NOT NULL
            )");

            // [SQLite migration] Index used by the chat room query and by the
            // real-time stream when it looks for new messages.
            $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_messages_pair
                              ON messages (msg_sender_id, msg_resciver_id, msg_id)");
        }

        public function __destruct()
        {
            if($this->stmt !== null){
                $this->stmt = null;
            }
            if($this->pdo !== null){
                $this->pdo = null;
            }
        }

        public function query($sql){
            $this->stmt = $this->pdo->prepare($sql);
        }

        public function bind($param, $value, $type = null){
            if(is_null($type)){
                switch(true){
                    case is_int($value):
                        $type = PDO::PARAM_INT;
                        break;
                    case is_bool($value):
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_null($value):
                        $type = PDO::PARAM_NULL;
                        break;
                    default:
                       $type = PDO::PARAM_STR;
                }
            }
            $this->stmt->bindValue($param ,$value, $type);
        }

        public function execute(){
            $this->stmt->execute();
        }

        // Fetch All
        public function fetchAll(){
            $this->stmt->execute();
            $result = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        public function fetch(){
            $this->stmt->execute();
            $result = $this->stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        public function rowCount(){
            return $this->stmt->rowCount();
        }

        public function close(){
            $this->pdo = null;
        }
        // public function joinSelect($userId){

        // }
    }
