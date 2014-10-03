<?php

namespace models;

require_once("./src/models/User.php");
require_once("./src/models/Repository.php");

class UserRepository extends Repository {
    private static $username = "Username";
    private static $password = "Password";

    public function __construct() {
        $this->dbTable = "user";
    }

    /**
     * @param User $user - a user object as input parameter from a view.
     * Adding a new user to the database, using parameters to prevent SQL-injections.
     */
    public function add(User $user) {
        $db = $this->connection();

        $sql = "INSERT INTO $this->dbTable(" . self::$username . ", " . self::$password . ") VALUES (?, ?)";
        $params = array($user->getUsername(), $user->getPassword());

        $query = $db->prepare($sql);
        $query->execute($params);
    }

    public function get(User $user) {
        $db = $this->connection();

        $sql = "SELECT * FROM $this->dbTable WHERE " . self::$username . " = ?";
        $params = array($user->getUsername());

        $query = $db->prepare($sql);
        $query->execute($params);

        $result = $query->fetch();

        if($result)
        {
            return $result;
        }

        return NULL;
    }
}