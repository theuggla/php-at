<?php

namespace loginmodule\persistance;

class LoginModulePersistance implements \loginmodule\persistance\IPersistance
{

    private static $dbconnection;

    public function __construct($dbconnection)
    {
        self::$dbconnection = $dbconnection;
    }

    public function didTempUserExpire(string $username, string $password) : bool
    {
        $query='SELECT * FROM Cookie WHERE BINARY username="' . $username . '" AND cookiepassword="' . $cookiePassword . '"';
        $result = self::$dbconnection->query($query);
             
        $cookie = $result->fetch_object();

        if ($result->num_rows <= 0 || $cookie->expiry < time()) {
            return false;
        } else {
            return true;
        }
    }

    public function saveTempUser(string $username, string $password, int $timestamp)
    {
        $cookiepassword = self::$dbconnection->real_escape_string($cookiepassword);
        $username = self::$dbconnection->real_escape_string($username);

        $query = 'INSERT INTO Cookie (cookiepassword, username, expiry) VALUES ("' . $cookiepassword . '", "' . $username . '", ' . $timestamp . ')';
    
        self::$dbconnection->query($query);
    }

    public function doesUserExist(string $username) : bool
    {
        $result = $this->getUserByUsername($username);
            
        if ($result->num_rows <= 0) {
            return false;
        } else {
            return true;
        }
    }

    public function getUser(string $username, string $password) : bool
    {
        $result = $this->getUserByUsername($username);
            
        if ($result->num_rows <= 0) {
            return false;
        } else {
            return true;
        }
    }

    public function saveUser(string $username, string $password)
    {
        $username = self::$dbconnection->real_escape_string($username);
        $query = 'INSERT INTO User (username, password) VALUES ("' . $username . '", "' . $password . '")';
        
        $result = self::$dbconnection->query($query);
    }

    private function getUserByUsername(string $username)
    {
        $query='SELECT * FROM User WHERE BINARY username="' . $username . '"';
        $result = self::$dbconnection->query($query);

        return $result;
    }
}
