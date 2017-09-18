<?php

namespace controller;

    class UserController {
        private $loginController = 'UserController::LoginController';
        private $logoutController = 'UserController::Logoutontroller';
        private $registerController = 'UserController::RegisterController';

        public function __construct(
                                $loginController, 
                                $logoutController,
                                $registerController) {

            $this->loginController = $loginController;
            $this->logoutController = $logoutController;
            $this->registerController = $registerController;

        }

        public function greetUserCorrectly() {
            $isLoggedIn = isset($_SESSION["isLoggedIn"]) ? $_SESSION["isLoggedIn"] : false ;    
            $wantsToRegister = isset($_GET["register"]);

            if ($isLoggedIn) {
                echo 'is logged in';
            } else if ($wantsToRegister) {
                echo 'wants to register';
            } else {
                $this->loginController->greetUser();
            }
        }
    }

?>