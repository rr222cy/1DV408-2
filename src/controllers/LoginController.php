<?php

namespace controllers;

require_once('src/loginSystem.php');

use models\User;
use models\UserRepository;
use services\ClientService;
use services\SessionService;
use views\LoginView;
use views\UserView;
use views\RegisterView;

class LoginController {
    private $userRepository;
    private $user;
    /**
     * @var LoginView
     */
    private $loginView;
    /**
     * @var UserView
     */
    private $userView;
    /**
     * @var RegisterView
     */
    private $registerView;

    private $signup = false;

    public function __construct(UserRepository $userRepository, LoginView $loginView, RegisterView $registerView, UserView $userView, User $user,
                                ClientService $clientService, SessionService $sessionService) {
        $this->registerView = $registerView;
        $this->loginView = $loginView;
        $this->userView = $userView;
        $this->user = $user;
        $this->userRepository = $userRepository;

        $sessionService->setClientIdentifier($clientService->getClientIdentifier());

    }

    private function handleInput() {

        if (!$this->user->isLoggedIn() and $this->loginView->isUserRemembered())
        {
            //if ($this->user->logInWithKey($this->loginView->getRememberedKey()))
            //{
                $result = $this->userRepository->getKey($this->loginView->getRememberedKey());
                if($result != NULL)
                {
                    $this->user->logInWithKey($this->loginView->getRememberedKey());

                    $_SESSION["Username"] = $result["Username"];
                    $this->userView->setLoginSucceededRemembered();
                }
            //}
            else
            {
                $this->loginView->forgetUser();
                $this->loginView->setLoginErrorRemembered();
            }
        }

        if ($this->user->isLoggedIn())
        {
            if ($this->userView->isAuthenticatingUser())
            {
                $this->user->logOut();
                $this->loginView->forgetUser();
                $this->loginView->setHaveLoggedOut();
            }
        }
        else
        {
            // If a user is to be added
            if ($this->registerView->isAddingUser())
            {
                if($this->registerView->validateUser())
                {
                    $this->userRepository->add($this->registerView->getUsername(), $this->registerView->getPassword());

                    $this->signup = true;
                    $this->loginView->setSignupMessage();
                }
             }
            // If a user is to be logged in
            elseif ($this->loginView->isAuthenticatingUser())
            {
                $username = $this->loginView->getUsername();
                $password = $this->loginView->getPassword();

                $this->userRepository->get($username);


                if ($this->user->logIn($username, md5($password))) {
                    if ($this->loginView->shouldUserBeRemembered()) {
                        $this->loginView->rememberUser();
                        $this->userView->setLoginSucceededRemembering();
                        $_SESSION["Username"] = $username;
                    } else {
                        $_SESSION["Username"] = $username;
                        $this->userView->setLoginSucceeded();
                    }
                } else {
                    $this->loginView->setLoginError();
                }
            }
        }

    }

    private function handleOutput() {
        if(isset($_GET["register"]) && $this->signup === false)
        {
            return $this->registerView;
        }
        else
        {
            if ($this->user->isLoggedIn())
            {
                return $this->userView;
            }
            else
            {
                return $this->loginView;
            }
        }
    }

    public function render() {
        $this->handleInput();
        return $this->handleOutput();
    }
}