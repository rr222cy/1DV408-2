<?php

namespace models;

require_once("./src/models/User.php");
require_once("./src/models/Repository.php");

class UserRepository extends Repository {
    private static $username = "Username";
    private static $password = "Password";
    private static $key = "UserKey";

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

        $randomKey = md5(time());

        $db = $this->connection();

        $sql = "INSERT INTO $this->dbTable(" . self::$username . ", " . self::$password . ", " . self::$key . ") VALUES (?, ?, ?)";
        $params = array($username, md5($password), $randomKey);

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
            $this->user->setUser($result[self::$username], $result[self::$password], $result[self::$key]);
            return $result;
        }

        return NULL;
    }

    public function getKey($key) {
        $db = $this->connection();

        $sql = "SELECT * FROM $this->dbTable WHERE " . self::$key . " = ?";
        $params = array($key);

        $query = $db->prepare($sql);
        $query->execute($params);

        $result = $query->fetch();

        if($result)
        {
            $this->user->setUser($result[self::$username], $result[self::$password], $result[self::$key]);
            return $result;
        }

        return NULL;
    }
}