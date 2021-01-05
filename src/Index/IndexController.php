<?php

namespace Teca\Index;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Teca\Post\ThreadController;
use Teca\Tag\TagController;
use Teca\User\UserController;

class IndexController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexAction() : object
    {
        $page = $this->di->get("page");

        $tagController = new TagController();
        $tagController->setDi($this->di);

        $userController = new UserController();
        $userController->setDi($this->di);

        $threadController = new ThreadController();
        $threadController->setDi($this->di);

        $tags = $tagController->getTags();
        $users = $userController->getUsers();
        $threads = $threadController->getThreads();

        $page->add("user/all", [
            "users" => $users,
            "prefix" => "",
        ]);

        $page->add("tag/all", [
            "tags" => $tags,
            "prefix" => "",
        ]);

        $page->add("post/threads", [
            "threads" => $threads,
            "prefix" => "",
        ]);

        return $page->render([
            "title" => "Hem",
        ]);
    }
}
