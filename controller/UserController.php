<?php

namespace controller;

    class UserController {
        private static $currentMessage = 'LoginModule::UserController::CurrentMessage';

        private $loginUserController = 'UserController::LoginController';
        private $registerUserController = 'UserController::RegisterController';

        private $user  = 'UserController::User';
        private $flashMessage = 'UserController::FlashMessage';
        private $lastUsername = 'UserController::LastUsername';

        private $externalView;

        private $displayLoginForm = false;
        private $displayRegisterForm = false;

        public function __construct($user, $loginUserController, $registerUserController, $externalView) 
        {
            $this->loginUserController = $loginUserController;
            $this->registerUserController = $registerUserController;

            $this->externalView = $externalView;

            $this->user = $user;
        }

        public function listenForUserAccessAttempt()
        {
            $this->delegateControlDependingOnUseCase();
            $this->displayCorrectView();
        }

        private function delegateControlDependingOnUseCase() 
        {
            if ($this->user->isLoggedIn() && $this->user->hasNotBeenHijacked()) 
            { 
                $this->determineLogoutAttempt(); 
                $this->flashMessage = $this->loginUserController->getCurrentMessage();      
            } 
            else if ($this->externalView->userWantsToRegister()) 
            {
                $this->displayRegisterForm = true;
                $this->determineRegisterAttempt();
                $this->flashMessage = $this->registerUserController->getCurrentMessage();
            } 
            else 
            {
                $this->displayLoginForm = true;
                $this->determineLoginAttempt();   
                $this->flashMessage = $this->loginUserController->getCurrentMessage();
            }
        }

        private function displayCorrectView() 
        {
            if ($this->displayRegisterForm) 
            {
                $this->renderRegisterForm();
            }
            else
            {
                $this->renderDependingOnLoginStatus();
            }
        }

        private function determineLogoutAttempt()
        {
            $this->loginUserController->handleLoggedInUser();

            if ($this->loginUserController->logoutSuccessful()) 
            {
                $this->displayLoginForm = true;
            }
        }

        private function determineRegisterAttempt()
        {
            $this->registerUserController->handleUserRegisterAttempt();;

            if ($this->registerUserController->registrationWasSuccessful()) 
            {
                $this->displayRegisterForm = false;
                header("Location: /");
            }

        }

        private function determineLoginAttempt()
        {
            $this->loginUserController->handleLoggedOutUser();

            if ($this->loginUserController->loginSuccessful()) 
            {
                $this->displayLoginForm = false;
            }
        }

        private function renderDependingOnLoginStatus() 
        {
            $loggedin;

            if ($this->user->isLoggedIn() && $this->user->hasNotBeenHijacked()) {
                $loggedIn = true;
            } else {
                $loggedIn = felse;
            }

            $this->lastUsername = $this->user->getLatestUsername();
            $currentHTML = $this->loginUserController->getHTML($this->flashMessage, $this->lastUsername);
            $this->externalView->renderToOutput($loggedIn, $currentHTML);
        }

        private function renderRegisterForm() 
        {
            $this->lastUsername = $this->user->getLatestUsername();
            $currentHTML = $this->registerUserController->getHTML($this->flashMessage, $this->lastUsername);
            $this->externalView->renderToOutput($this->user->isLoggedIn(), $currentHTML);
        }
    }