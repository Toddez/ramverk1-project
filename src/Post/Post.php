<?php

namespace Teca\Post;

use Anax\DatabaseActiveRecord\ActiveRecordModel;
use Teca\Post\PostType;
use Teca\Vote\Vote;

class Post extends ActiveRecordModel
{
    protected $tableName = "Post";

    public $id;
    public $author;
    public $title;
    public $content;
    public $type;
    public $thread;
    public $parent;
    public $creation;
    public $answer;

    public function score($di) : int
    {
        $vote = new Vote();
        $vote->setDb($di->get("dbqb"));
        $votes = $vote->findAllWhere("post = ?", [$this->id]);

        $sum = 0;
        foreach ($votes as $vote) {
            $sum += $vote->value;
        }

        return $sum;
    }

    public function hasAnswer($di) : bool
    {
        $post = new Post();
        $post->setDb($di->get("dbqb"));
        $post = $post->findWhere("thread = ? AND type = ? AND answer = ?", [$this->id, PostType::ANSWER, true]);

        if ($post->id) {
            return true;
        }

        return false;
    }
}
