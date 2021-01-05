<?php

namespace Teca\User;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected $model;

    protected function setUp()
    {
        $this->model = new User();
    }

    public function testTableColumns()
    {
        $this->assertTrue(property_exists($this->model, "id"));
        $this->assertTrue(property_exists($this->model, "name"));
        $this->assertTrue(property_exists($this->model, "password"));
        $this->assertTrue(property_exists($this->model, "avatar"));
    }
}
