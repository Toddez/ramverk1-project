<?php

namespace Teca\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Teca\User\User;

class LoginForm extends FormModel
{
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => "LoginForm",
                "legend" => "Logga in",
            ],
            [
                "name" => [
                    "type" => "text",
                    "label" => "Namn",
                    "validation" => ["not_empty"],
                ],
                "password" => [
                    "type" => "password",
                    "label" => "Lösenord",
                    "validation" => ["not_empty"],
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Logga in",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }

    public function callbackSubmit() : bool
    {
        $name = $this->form->value("name");
        $password = $this->form->value("password");

        $user = new User();
        $user->setDb($this->di->get("dbqb"));

        $authorized = $user->verifyPassword($name, $password);

        if (!$authorized) {
            $this->form->rememberValues();
            $this->form->addOutput("Användarnamn eller lösenord matchar inte.", "bad");
            return false;
        }

        $user->login($this->di);
        return true;
    }

    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("")->send();
    }
}
