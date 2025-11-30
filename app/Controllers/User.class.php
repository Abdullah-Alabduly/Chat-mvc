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
                    }
                }else echo "all data form required";

            } else if($_SERVER['REQUEST_METHOD'] === 'GET') Controller::view('register');       
        }
    }