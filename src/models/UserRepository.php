<?php

namespace models;

require_once("./src/models/User.php");
require_once("./src/models/Repository.php");

class UserRepository extends Repository {
    private static $userID = "ID";
    private static $username = "Username";
    private static $password = "Password";

    public function __construct() {
        $this->dbTable = "user";
    }

   
}