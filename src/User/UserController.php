<?php

namespace Teca\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Teca\User\HTMLForm\LoginForm;
use Teca\User\HTMLForm\RegisterForm;

class UserController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexAction() : object
    {
        // TODO: Redirect to login if not logged in
        // TODO: Redirect to profile if logged in

        $page = $this->di->get("page");
        $form = new LoginForm($this->di);
        $form->check();

        $page->add("user/login", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Logga in",
        ]);
    }

    public function loginAction() : object
    {
        $page = $this->di->get("page");
        $form = new LoginForm($this->di);
        $form->check();

        $page->add("user/login", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Logga in",
        ]);
    }

    public function registerAction() : object
    {
        $page = $this->di->get("page");
        $form = new RegisterForm($this->di);
        $form->check();

        $page->add("user/register", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Registrera",
        ]);
    }
}
