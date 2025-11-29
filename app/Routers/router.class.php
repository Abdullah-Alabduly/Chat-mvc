<?php
    class Router {
        private $Controller = "Page";
        private $method = "index";
        // private $param = [];

        private $routes= [
        '/' =>'Home',
        'login' =>'User',
        'register' => 'User',
        'reg' => 'User',
    ];

        public function __construct()
        {
            $url = $this->getUrl();
            
            $this->routToController($url);
        }
        public function getUrl(){
            if(isset($_SERVER['REQUEST_URI'])){
                $url = parse_url($_SERVER['REQUEST_URI'])['path'];
                $url = filter_var($url, FILTER_SANITIZE_URL);
                
                $url = trim($url, '/');
                // echo $url;
                // $url = explode('/', $url);
                return $url;
            }
        }
        function routToController($url){

            if($url != null){  
                
                $url = strtolower($url);   
                                
                if(array_key_exists($url, $this->routes)){
                    
                    $this->Controller = $this->routes[$url];
                    
                }        
            }
            require_once 'app/Controllers/'.$this->Controller.'.class.php';

                if(method_exists($this->Controller, $url)){
                    
                    $this->method = $url;
            }          
             $class = $this->Controller;
             $func = $this->method;
            $class::$func();
        }
    }