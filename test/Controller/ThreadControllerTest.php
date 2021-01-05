<?php

namespace Teca\Post;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;
use Teca\User\User;

class ThreadControllerTest extends TestCase
{
    protected $di;
    protected $controller;
    protected $thread;
    protected $answer;

    protected function setUp()
    {
        global $di;

        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $this->di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        $di = $this->di;

        $this->controller = new ThreadController();
        $this->controller->setDI($this->di);

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->name = "test";
        $user->avatar = "test";
        $user->setPassword("123");
        $user->save();
        $this->user = $user;

        $thread = new Post();
        $thread->setDb($this->di->get("dbqb"));
        $thread->author = $user->id;
        $thread->type = PostType::THREAD;
        $thread->creation = time();
        $thread->save();
        $this->thread = $thread;

        $answer = new Post();
        $answer->setDb($this->di->get("dbqb"));
        $answer->author = $user->id;
        $answer->thread = $thread->id;
        $answer->type = PostType::ANSWER;
        $answer->creation = time();
        $answer->save();
        $this->answer = $answer;
    }

    public function testIndexActionGet()
    {
        $res = $this->controller->indexAction();
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $this->assertContains("<title>Samtliga frågor", $body);
        $this->assertContains("Samtliga frågor:", $body);
    }

    public function testNewActionGet()
    {
        $res = $this->controller->newAction();
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $this->assertContains("<title>Ny fråga", $body);
        $this->assertContains("<legend>Ny fråga", $body);
    }

    public function testAnswerActionGet()
    {
        $res = $this->controller->answerAction($this->thread->id);
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $this->assertContains("<title>Svara", $body);
        $this->assertContains("<legend>Svara", $body);
    }

    public function testCommentActionGet()
    {
        $res = $this->controller->commentAction($this->thread->id, $this->answer->id);
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $this->assertContains("<title>Kommentera", $body);
        $this->assertContains("<legend>Kommentera", $body);
    }

    public function testViewActionGet()
    {
        $res = $this->controller->viewAction($this->thread->id);
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $this->assertContains("<div class=\"thread inspect", $body);
        $this->assertContains("<a class=\"addAnswer", $body);
    }
}
