<?php

namespace Teca\Vote;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

class Vote extends ActiveRecordModel
{
    protected $tableName = "Vote";

    public $id;
    public $post;
    public $user;
    public $value;
}
