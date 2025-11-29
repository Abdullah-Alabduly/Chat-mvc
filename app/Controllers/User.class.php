<?php
    session_start();
    class User{

        private static $userModel = null;
        private static $userData = [];
    

        public static function index(){

            Controller::view('login');
        }
        
         

        public static function register(){
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                self::fetchData($_POST, 'image');
                self::cleanInputs();
                $isEmpty = self::checkEmptyInputs();

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
                                }else{
                                    echo "Something went wrong !!";

                                }

                            } else{
                                echo $response;
                            }
                           
                        }
                    }
                }else {
                    echo "all data form required";
                }



            } else if($_SERVER['REQUEST_METHOD'] === 'GET'){
                Controller::view('register');
            }   
        }

        public static function fetchData(array $inputs, $ignoredKey){
            foreach ($inputs as $key => $value) {
                if($key !== $ignoredKey){
                    self::$userData[$key] = $value;
                }
            }
        }
        public static function filterValue(string $value):string
        {
                $value = trim($value);
                $value = strip_tags($value);
                $value = htmlentities($value, ENT_QUOTES, 'UTF-8');
                $value = stripslashes($value);
                $value = filter_var($value, FILTER_UNSAFE_RAW);
            
            return $value;
        }
        public static function cleanInputs(){
            foreach(self::$userData as $value){
                $value = self::filterValue($value);
            }
        }

        public static function checkEmptyInputs():bool{
           $emptyInput = false;
            foreach (self::$userData as $value) {
                if(empty($value)){
                    $emptyInput = true;
                    return $emptyInput;
                }
            }
            return $emptyInput;
        }

        public static function handleImage(){
            if(isset($_FILES['image'])){
                $img_name = $_FILES['image']['name'];
                $tmp_name = $_FILES['image']['tmp_name'];

                //getting the image extension
                $img_ex = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
                $extensions = ['jpg', 'jpeg', 'png'];

                if(in_array($img_ex, $extensions)){
                    $time = time();
                    $new_img_name = $time.'_'.$img_name;

                    if(move_uploaded_file($tmp_name, "app/images/".$new_img_name)){
                        return $new_img_name;
                    }else {
                        return "something went wrong in uploading image";
                        }
                }else{
                        return 'Please select an image file - png, jpg, jpeg';
                    }
            }else{
                    return "Please select an image file";
                }
        }
    }

