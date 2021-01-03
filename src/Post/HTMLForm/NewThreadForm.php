<?php

namespace Teca\Post\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Teca\Post\Post;

class NewThreadForm extends FormModel
{
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => "ThreadForm",
                "legend" => "Ny fråga",
            ],
            [
                "title" => [
                    "type" => "text",
                    "label" => "Titel",
                    "validation" => [],
                ],
                "content" => [
                    "type" => "textarea",
                    "label" => "Fråga",
                    "validation" => [],
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Ställ fråga",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }

    public function callbackSubmit() : bool
    {
        return false;
    }

    public function callbackSuccess()
    {
        // TODO: Redirect to thread
    }
}
