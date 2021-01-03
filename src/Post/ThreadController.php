<?php

namespace Teca\Post;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Teca\Post\HTMLForm\NewThreadForm;
use Teca\User\User;

class ThreadController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexAction() : object
    {
        $page = $this->di->get("page");

        $page->add("post/threads", [
        ]);

        return $page->render([
            "title" => "Samtliga frågor",
        ]);
    }

    public function newAction() : object
    {
        $user = new User();
        if (!$user->authorized($this->di)) {
            $this->di->get("response")->redirect("user/login")->send();
        }

        $page = $this->di->get("page");
        $form = new NewThreadForm($this->di);
        $form->check();

        $page->add("post/newthread", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Ny fråga",
        ]);
    }
}
