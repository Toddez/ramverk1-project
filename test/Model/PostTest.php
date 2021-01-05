<?php

namespace Teca\Post;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    protected $model;

    protected function setUp()
    {
        $this->model = new Post();
    }

    public function testTableColumns()
    {
        $this->assertTrue(property_exists($this->model, "id"));
        $this->assertTrue(property_exists($this->model, "author"));
        $this->assertTrue(property_exists($this->model, "title"));
        $this->assertTrue(property_exists($this->model, "content"));
        $this->assertTrue(property_exists($this->model, "type"));
        $this->assertTrue(property_exists($this->model, "thread"));
        $this->assertTrue(property_exists($this->model, "parent"));
        $this->assertTrue(property_exists($this->model, "creation"));
    }
}
