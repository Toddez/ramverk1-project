<?php

namespace Teca\Vote;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

class Vote extends ActiveRecordModel
{
    protected $tableName = "Vote";

    public $user;
    public $post;
    public $value;
}
