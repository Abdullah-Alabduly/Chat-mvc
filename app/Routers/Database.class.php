<?php
    class Database
    {
        private $connect;
        
        public function getConnect()
        {
            return $this->connect;
        }
        public function __construct($db_info)
        {
            try {
                $this->connect = mysqli_connect(
                $db_info['host'],
                $db_info['uname'],
                $db_info['password'],
                $db_info['db_name'],
                $db_info['port']
            );
            } catch (EXCEPTION $e) {
                throw new Exception("Connecting Failed", $e->getMessage());               
            }
        }
        public function query($sql)
        {
            $query = mysqli_query($GLOBALS['conn'],$sql);
            
            return $query;
        }
        public function rowCount($query)
        {
            return mysqli_num_rows($query);
        }
    }














    // class Database
    // {
    //     private $db_host = DB_HOST;
    //     private $db_port = DB_PORT;
    //     private $db_user =  DB_USER;
    //     private $db_password = DB_PASSWORD;
    //     private $db_name = DB_NAME;

    //     private $pdo;
    //     private $stmt;

    //     public function __construct()
    //     {
    //         $dsn = 'mysqli:'.$this->db_host.';db_name = '.$this->db_name;

    //         try
    //         {
    //             $this->pdo = new PDO($dsn,$this->db_user,$this->db_password, [
    //                 PDO::ATTR_DEFUALT_FETCH_MODE =>PDO::FETCH_ASSOC
    //             ]);
    //         }catch(PDOException $e)
    //         {
    //             die('there is an essue:'.$e->getMessage());
    //         }

    //     }
    //     public function __destruct()
    //     {
    //         if($this->stmt !== null)
    //         {
    //             $this->stmt = null;
    //         }
    //         if($this->pdo !==null)
    //         {
    //             $this->pdo = null;
    //         }
    //     }
    //     public function query($sql)
    //     {
    //         $this->stmt = $this->pdo->prepare($sql);
    //     }
    //     public function bind($param,$value,$type = null)
    //     {
    //         if(is_null($type))
    //         {
    //             switch(true)
    //             {
    //                 case is_int($value):
    //                     $type = PDO::PARAM_INT;
    //                     break;
    //                 case is_bool($value):
    //                     $type = PDO::PARAM_BOOL;
    //                     break;
    //                 case is_null($value):
    //                     $type = PDO::PARAM_NULL;
    //                     break;
    //                 default:
    //                     $type = PDO::PARAM_STR;
    //             }
    //         }
    //         $this->stmt->bindValue($param, $value, $type);
    //     }
    //     public function execute(){
    //         $this->stmt->execute();
    //     }
    //     public function fetchAll(){
    //         $this->stmt->execute();
    //         $result = $this->stmt->fetcAll();

    //         return $result;
    //     }

    //     public function fetch(){
    //         $this->stmt->execute();
    //         $result =$this->stmt->fetch();
    //         return $result;
    //     }

    //     public function rowCount()
    //     {
    //         return $this->stmt->rowCount();
    //     }
    // }