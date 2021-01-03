<?php

namespace Teca\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Teca\User\HTMLForm\ProfileForm;
use Teca\User\HTMLForm\LoginForm;
use Teca\User\HTMLForm\RegisterForm;
use Teca\User\User;

class UserController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexAction() : object
    {
        if (!User::authorized($this->di)) {
            $this->di->get("response")->redirect("user/login")->send();
        }

        $page = $this->di->get("page");
        $form = new ProfileForm($this->di);
        $form->check();

        $page->add("user/profile", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Logga in",
        ]);
    }

    public function logoutAction() : object
    {
        User::logout($this->di);
        $this->di->get("response")->redirect("user")->send();
    }

    public function loginAction() : object
    {
        if (User::authorized($this->di)) {
            $this->di->get("response")->redirect("user")->send();
        }

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
        if (User::authorized($this->di)) {
            $this->di->get("response")->redirect("user")->send();
        }

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
