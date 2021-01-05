<?php

namespace Teca\Index;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

class IndexControllerTest extends TestCase
{
    protected $di;
    protected $controller;

    protected function setUp()
    {
        global $di;

        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $this->di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        $di = $this->di;

        $this->controller = new IndexController();
        $this->controller->setDI($this->di);
    }

    public function testIndexActionGet()
    {
        $res = $this->controller->indexAction();
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $this->assertContains("<title>Hem", $body);
        $this->assertContains("<div>Samtliga användare", $body);
        $this->assertContains("<div>Samtliga taggar", $body);
        $this->assertContains("<div>Samtliga frågor", $body);
    }
}
