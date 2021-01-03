<?php

namespace Teca\User;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

class User extends ActiveRecordModel
{
    protected $tableName = "User";

    public $id;
    public $name;
    public $password;
    public $avatar;

    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword($name, $password)
    {
        $this->find("name", $name);
        return password_verify($password, $this->password);
    }

    public function login($di)
    {
        $session = $di->get("session");
        $session->set("authorized", true);
        $session->set("user", $this->name);
    }

    static function logout($di)
    {
        $session = $di->get("session");
        $session->set("authorized", false);
        $session->delete("user");
    }

    static function authorized($di) : bool
    {
        $session = $di->get("session");

        if ($session->get("authorized", false)) {
            return true;
        }

        return false;
    }

    static function currentUser($di) : User
    {
        $session = $di->get("session");

        if (User::authorized($di)) {
            $user = new User();
            $user->setDb($di->get("dbqb"));
            $user->find("name", $session->get("user"));

            return $user;
        }

        return new User();
    }
}