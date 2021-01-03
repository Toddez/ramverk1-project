<?php

namespace Teca\Post;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

class Post extends ActiveRecordModel
{
    protected $tableName = "Post";

    public $id;
    public $author;
    public $type;
    public $thread;
    public $parent;
    public $creation;
    public $answer;
}
