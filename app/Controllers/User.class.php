<?php
    // [Real-time] Only start the session if none is active yet, so this file can
    // be autoloaded from inside the long-lived stream without a PHP notice.
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    class User  extends UserHelper{
        private static $userModel = null;
        private static $userData = [];

        public static function index(){
            Controller::view('register');
        }
        public static function register(){
            if(isset($_SESSION['unique_id'])){
                    header("location: profile");
            }else
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
                                // [Password hashing] Only the hash of the password is
                                // stored; the plain value never reaches the database.
                                self::$userData['password'] = password_hash(self::$userData['password'], PASSWORD_DEFAULT);
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
            if(isset($_SESSION['unique_id'])){
                    header("location: profile");
            }else
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
                            self::$userModel->updateStatus($_SESSION['unique_id'], 1);
                            echo "Success";
                        }else echo "Email or password is wrong";

                 }else echo "invalid email";
                }else echo "All Data are Required";


            }else if($_SERVER['REQUEST_METHOD'] === 'GET'){
                Controller::view('login');
            }
            
        }
        // [Real-time] Logout is now a normal link (no AJAX call), so it must work
        // with a GET request and always end with a redirect to the login page.
        public static function logout(){
            if(isset($_SESSION['unique_id'])){
                self::$userModel = Controller::model('UserModel');
                self::$userModel->updateStatus($_SESSION['unique_id'], 0);
            }
            session_unset();
            session_destroy();
            header("location: login");
            exit;
        }
    }
