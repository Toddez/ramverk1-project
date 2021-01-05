<?php

namespace Teca\Tag;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    protected $model;

    protected function setUp()
    {
        $this->model = new Tag();
    }

    public function testTableColumns()
    {
        $this->assertTrue(property_exists($this->model, "id"));
        $this->assertTrue(property_exists($this->model, "value"));
    }
}
