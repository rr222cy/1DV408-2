<?php

namespace controllers;

require_once('src/loginSystem.php');

use models\User;
use services\ClientService;
use services\SessionService;
use views\LoginView;
use views\UserView;
use views\RegisterView;

class LoginController {
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

    public function __construct(LoginView $loginView, RegisterView $registerView, UserView $userView, User $user,
                                ClientService $clientService, SessionService $sessionService) {
        $this->registerView = $registerView;
        $this->loginView = $loginView;
        $this->userView = $userView;
        $this->user = $user;

        $sessionService->setClientIdentifier($clientService->getClientIdentifier());
    }

    private function handleInput() {

        if (!$this->user->isLoggedIn() and $this->loginView->isUserRemembered()) {
            if ($this->user->logInWithKey($this->loginView->getRememberedKey())) {
                $this->userView->setLoginSucceededRemembered();
            } else {
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
                $username = $this->registerView->getUsername();
                if($username == $this->user->getUsername())
                {
                    $this->registerView->setUsernameNotAvailableError();
                    
                }
                else
                {
                    echo "all is good";
                }
             }
            // If a user is to be logged in
            elseif ($this->loginView->isAuthenticatingUser())
            {
                $username = $this->loginView->getUsername();
                $password = $this->loginView->getPassword();

                if ($this->user->logIn($username, $password)) {
                    if ($this->loginView->shouldUserBeRemembered()) {
                        $this->loginView->rememberUser();
                        $this->userView->setLoginSucceededRemembering();
                    } else {
                        $this->userView->setLoginSucceeded();
                    }
                } else {
                    $this->loginView->setLoginError();
                }
            }
        }
    }

    private function handleOutput() {

        if(isset($_GET["register"]))
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
