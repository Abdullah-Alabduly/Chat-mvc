<?php session_start();

    
    class User  extends UserHelper{
        private static $userModel = null;
        private static $userData = [];

        public static function index(){
            Controller::view('register');
        }
        public static function register(){
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                self::fetchData($_POST, self::$userData, 'image');
                self::cleanInputs(self::$userData);
                $isEmpty = self::checkEmptyInputs(self::$userData);

                if(!$isEmpty){
                    if(filter_var(self::$userData['email'], FILTER_VALIDATE_EMAIL)){
                        self::$userModel = Controller::model('UserModel');
                        if(self::$userModel->findByEmail(self::$userData['email']) >0){
                            echo 'This Email Already Exist !';
                        }else{
                            // dealing with the upload image
                            $response = self::handleImage();
                            if(preg_match('/^[0-9]/', $response)){
                                $status = 1;
                                $random_id = rand(time(), 10000000);
                                self::$userData = array_merge(['unique_id'=> $random_id], self::$userData);
                                self::$userData = array_merge(self::$userData, ['img' => $response]);
                                self::$userData = array_merge(self::$userData, ['status' => $status]);
                                $row = self::$userModel->addNewUser(self::$userData);
                                if($row >0 ){
                                    $_SESSION['unique_id'] = $random_id;
                                    echo "Success";
                                }else echo "Something went wrong !!";
                            } else echo $response;                       
                        }
                    }else echo "invalid email";
                }else echo "all data form required";

            } else if($_SERVER['REQUEST_METHOD'] === 'GET') Controller::view('register');       
        }

        public static function login(){
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                self::fetchData($_POST, self::$userData);
                self::cleanInputs(self::$userData);
                $isEmpty = self::checkEmptyInputs(self::$userData);
                 if(!$isEmpty){
                    if(filter_var(self::$userData['email'], FILTER_VALIDATE_EMAIL)){
                        self::$userModel = Controller::model('UserModel');
                        $rowUser = self::$userModel->getUserLog(self::$userData['email'], self::$userData['password']);
                        if(!empty($rowUser)){
                            $_SESSION['unique_id'] = $rowUser['unique_id'];
                            echo "Success";
                        }else echo "Email or password is wrong";

                 }else echo "invalid email";
                }else echo "All Data are Required";


            }else if($_SERVER['REQUEST_METHOD'] === 'GET'){
                Controller::view('login');
            }
            
        }
    }



    // session_start();
    // include_once 'config.php';
    // $email = mysqli_real_escape_string($conn,$_POST['email']);
    // $password = mysqli_real_escape_string($conn,$_POST['password']);

    // if(!empty($email) && !empty($password)){

    //         $sql =mysqli_query($conn,"SELECT * FROM users WHERE email = '{$email}' AND password = '{$password}'");
    //         if(mysqli_num_rows($sql) > 0){
    //             $row = mysqli_fetch_assoc($sql);
    //             $_SESSION['unique_id'] = $row['unique_id'];
    //             $sql = "UPDATE users SET status = 1 WHERE unique_id = {$_SESSION['unique_id']}";
    //             mysqli_execute_query($conn, $sql);
    //             echo "Success";
    //         }else{
    //             echo "The email or the password is incorrect!";
    //         }

    // }else{
    //     echo "All inputs are required";
    // }