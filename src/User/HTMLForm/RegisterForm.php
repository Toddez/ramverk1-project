<?php

namespace Teca\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Teca\User\User;

class RegisterForm extends FormModel
{
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => "RegisterForm",
                "legend" => "Registrera",
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
                "password-confirm" => [
                    "type" => "password",
                    "label" => "Bekräfta lösenord",
                    "validation" => [
                        "match" => "password",
                        "not_empty"
                    ],
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Registrera",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }

    public function callbackSubmit() : bool
    {
        $name = $this->form->value("name");
        $password = $this->form->value("password");
        $passwordConfirm = $this->form->value("password-confirm");

        if ($password !== $passwordConfirm) {
            $this->form->rememberValues();
            return false;
        }

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("name", $name);

        if ($user->id) {
            $this->form->rememberValues();
            $this->form->addOutput('Användare finns redan', "bad");
            return false;
        }

        $user->name = $name;
        $user->setPassword($password);
        $user->save();
        $user->login($this->di);

        return true;
    }

    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("user")->send();
    }
}
