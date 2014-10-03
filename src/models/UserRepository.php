<?php

namespace models;

require_once("./src/models/User.php");
require_once("./src/models/Repository.php");

class UserRepository extends Repository {
    private static $username = "Username";
    private static $password = "Password";

    private $user;

    public function __construct(User $user) {
        $this->dbTable = "user";
        $this->user = $user;
    }

    /**
     * @param User $user - a user object as input parameter from a view.
     * Adding a new user to the database, using parameters to prevent SQL-injections.
     */
    public function add($username, $password) {
        $db = $this->connection();

        $sql = "INSERT INTO $this->dbTable(" . self::$username . ", " . self::$password . ") VALUES (?, ?)";
        $params = array($username, $password);

        $query = $db->prepare($sql);
        $query->execute($params);
    }

    public function get($username) {
        $db = $this->connection();

        $sql = "SELECT * FROM $this->dbTable WHERE " . self::$username . " = ?";
        $params = array($username);

        $query = $db->prepare($sql);
        $query->execute($params);

        $result = $query->fetch();

        if($result)
        {
            //$user = new \models\User($result[self::$username], $result[self::$password]);
            $this->user->setUser($result[self::$username], $result[self::$password]);
            return $result;
        }

        return NULL;
    }
}