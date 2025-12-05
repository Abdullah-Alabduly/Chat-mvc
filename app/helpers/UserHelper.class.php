<?php
class UserHelper{

    public static function filterValue(string &$value):string{
                $value = trim($value);
                $value = strip_tags($value);
                $value = htmlentities($value, ENT_QUOTES, 'UTF-8');
                $value = stripslashes($value);
                $value = filter_var($value, FILTER_UNSAFE_RAW);           
            return $value;
        }

    public static function cleanInputs(&$userData){
            foreach($userData as $value){
                $value = self::filterValue($value);
        }
    }
    public static function checkEmptyInputs($userData):bool
    {
           $emptyInput = false;
            foreach ($userData as $value) {
                if(empty($value)){
                    $emptyInput = true;
                    return $emptyInput;
                }
            }  return $emptyInput;
    }
    public static function fetchData(array $inputs, &$userData, $ignoredKey=""){
            foreach ($inputs as $key => $value) {
                if($key !== $ignoredKey){
                    $userData[$key] = $value;
                }
            }
        }
    public static function handleImage(){
            if(isset($_FILES['image'])){
                $img_name = $_FILES['image']['name'];
                $tmp_name = $_FILES['image']['tmp_name'];
                $img_ex = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
                $extensions = ['jpg', 'jpeg', 'png'];

                if(in_array($img_ex, $extensions)){
                    $time = time();
                    $new_img_name = $time.'_'.$img_name;
                    if(move_uploaded_file($tmp_name, "app/images/".$new_img_name)){
                        return $new_img_name;
                    }else return "something went wrong in uploading image";
                }else return 'Please select an image file - png, jpg, jpeg';
            }else return "Please select an image file";    
        }
}