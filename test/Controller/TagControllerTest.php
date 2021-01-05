<?php

namespace Teca\Tag;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

class TagControllerTest extends TestCase
{
    protected $di;
    protected $controller;
    protected $tag;

    protected function setUp()
    {
        global $di;

        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $this->di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        $di = $this->di;

        $this->controller = new TagController();
        $this->controller->setDI($this->di);

        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $tag->value = "test";
        $tag->save();
        $this->tag = $tag;
    }

    public function testIndexActionGet()
    {
        $res = $this->controller->indexAction();
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $this->assertContains("<title>Samtliga taggar", $body);
        $this->assertContains("<div>Samtliga taggar", $body);
    }

    public function testViewActionGet()
    {
        $res = $this->controller->viewAction($this->tag->id);
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $this->assertContains("taggen: test", $body);
        $this->assertContains("Samtliga frågor:", $body);
    }
}
