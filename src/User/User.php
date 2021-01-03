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

    public function gravatar($size = 200) : string
    {
        $hash = md5(strtolower(trim($this->avatar)));
        return "https://www.gravatar.com/avatar/" . $hash . "?s=" . $size . "&d=identicon";
    }

    public function login($di)
    {
        $session = $di->get("session");
        $session->set("authorized", true);
        $session->set("user", $this->name);
    }

    public function logout($di)
    {
        $session = $di->get("session");
        $session->set("authorized", false);
        $session->delete("user");
    }

    public function authorized($di) : bool
    {
        $session = $di->get("session");

        if ($session->get("authorized", false)) {
            $this->setDb($di->get("dbqb"));
            $this->find("name", $session->get("user"));

            if (!$this->id) {
                $this->logout($di);
                return false;
            }

            return true;
        }

        $this->logout($di);
        return false;
    }

    public function currentUser($di)
    {
        $session = $di->get("session");

        if ($this->authorized($di)) {
            $this->setDb($di->get("dbqb"));
            $this->find("name", $session->get("user"));
        }
    }
}
