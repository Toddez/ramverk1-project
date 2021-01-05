<?php

namespace Teca\User;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase
{
    protected $di;
    protected $controller;
    protected $user;

    protected function setUp()
    {
        global $di;

        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $this->di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        $di = $this->di;

        $this->controller = new UserController();
        $this->controller->setDI($this->di);

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->name = "test";
        $user->avatar = "test";
        $user->setPassword("123");
        $user->save();
        $this->user = $user;
    }

    public function testIndexActionGet()
    {
        $res = $this->controller->indexAction();
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $this->assertContains("<title>Profil", $body);
        $this->assertContains("<legend>Profil", $body);
    }

    public function testAllAction()
    {
        $res = $this->controller->allAction();
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $this->assertContains("<title>Samtliga användare", $body);
        $this->assertContains("<div>Samtliga användare:", $body);
    }

    public function testRegisterActionGet()
    {
        $res = $this->controller->registerAction();
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $this->assertContains("<legend>Registrera", $body);
    }

    public function testLoginActionGet()
    {
        $res = $this->controller->loginAction();
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $this->assertContains("<legend>Logga in", $body);
    }

    public function testLogoutActionGet()
    {
        $res = $this->controller->logoutAction();
        $this->assertInstanceOf("\Anax\Response\Response", $res);
    }

    public function testViewActionGet()
    {
        $res = $this->controller->viewAction($this->user->id);
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $this->assertContains("Ställda frågor:", $body);
        $this->assertContains("Besvarade frågor:", $body);
    }
}
