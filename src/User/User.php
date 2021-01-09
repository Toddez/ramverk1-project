<?php

namespace Teca\User;

use Anax\DatabaseActiveRecord\ActiveRecordModel;
use Teca\Post\Post;
use Teca\Post\PostType;
use Teca\Vote\Vote;

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

    public function score($di) : int
    {
        $post = new Post();
        $post->setDb($di->get("dbqb"));
        $posts = $post->findAllWhere("author = ?", [$this->id]);

        $score = 0;
        foreach ($posts as $post) {
            $score += 1 + $post->score($di);
        }

        return $score;
    }

    public function numThreads($di) : int
    {
        $post = new Post();
        $post->setDb($di->get("dbqb"));
        $posts = $post->findAllWhere("author = ? AND type = ?", [$this->id, PostType::THREAD]);

        return sizeof($posts);
    }

    public function numAnswers($di) : int
    {
        $post = new Post();
        $post->setDb($di->get("dbqb"));
        $posts = $post->findAllWhere("author = ? AND type = ?", [$this->id, PostType::ANSWER]);

        return sizeof($posts);
    }

    public function numComments($di) : int
    {
        $post = new Post();
        $post->setDb($di->get("dbqb"));
        $posts = $post->findAllWhere("author = ? AND type = ?", [$this->id, PostType::COMMENT]);

        return sizeof($posts);
    }

    public function numVotes($di) : int
    {
        $vote = new Vote();
        $vote->setDb($di->get("dbqb"));
        $votes = $vote->findAllWhere("user = ? AND value IN (?)", [$this->id, [1, -1]]);

        return sizeof($votes);
    }
}
