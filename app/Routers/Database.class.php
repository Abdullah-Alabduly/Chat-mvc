<?php

    // DB Conecction
    class Database{
        private $pdo;
        private $stmt;

        public function __construct($db_info)
        {
            $dsn = 'mysql:'.http_build_query($db_info,'',';');
            // $dsn = 'mysql:host='.$this->host .";port=".$this->port. ";dbname=" .$this->db_name;

            try {
                $this->pdo = new PDO($dsn,'root','');
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("There is an issue: ".$e->getMessage());
            }

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
            $result = $this->stmt->fetchAll();
            return $result;
        }
        public function fetch(){
            $this->stmt->execute();
            $result = $this->stmt->fetch();
            return $result;
        }

        public function rowCount(){
            return $this->stmt->rowCount();
        }
    }