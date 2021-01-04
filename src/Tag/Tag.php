<?php

namespace Teca\Tag;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

class Tag extends ActiveRecordModel
{
    protected $tableName = "Tag";

    public $id;
    public $value;
}
