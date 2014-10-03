<?php

namespace models;

require_once('src/loginSystem.php');

use services\SessionService;

class User {
    private static $sessionKey = 'User::username';
    /**
     * @var SessionService
     */
    private $session;

    //private $username = 'Admin';
    //private $password = 'Password';

    private $username;
    private $password;
    private $user;

    public function __construct(SessionService $session) {
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    /**
     * Sets values for username and password
     */

    public function setUser($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getKey() {
        return ':P';
    }

    /**
     * @return bool True if the user is logged in
     */
    public function isLoggedIn() {
        return $this->session->has(self::$sessionKey);
    }

    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function logIn($username, $password) {
        if ($username === $this->username and
            $password === $this->password) {
            $this->session->set(self::$sessionKey, $username);
            return true;
        }

        return false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function logInWithKey($key) {
        if ($key === $this->getKey()) {
            $this->session->set(self::$sessionKey, $this->getUsername());
            return true;
        }

        return false;
    }

    public function logOut() {
        $this->session->remove(self::$sessionKey);
    }
}