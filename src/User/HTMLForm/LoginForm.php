<?php

namespace Teca\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;

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
        // TODO: Check user info with database
        return false;
    }

    public function callbackSuccess()
    {
        // TODO: Redirect to profile
    }
}
