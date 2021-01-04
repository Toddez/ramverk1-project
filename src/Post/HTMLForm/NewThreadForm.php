<?php

namespace Teca\Post\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Teca\User\User;
use Teca\Post\Post;
use Teca\Post\PostType;

class NewThreadForm extends FormModel
{
    private $id;

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
                    "label" => "Fråga",
                    "placeholder" => "Hur gör jag x?",
                    "validation" => [
                        "not_empty"
                    ],
                ],
                "content" => [
                    "type" => "textarea",
                    "label" => "Beskrivning",
                    "placeholder" => "Längre beskrivning av problemet/frågan...",
                    "validation" => [
                        "not_empty"
                    ],
                ],
                "tags" => [
                    "type" => "text",
                    "label" => "Taggar (separera med kommatecken)",
                    "placeholder" => "regex,pandas,arrays,...",
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
        $title = $this->form->value("title");
        $content = $this->form->value("content");

        $user = new User();
        $user->currentUser($this->di);

        if (!$user->id) {
            return false;
        }

        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $post->author = $user->id;
        $post->title = $title;
        $post->content = $content;
        $post->type = PostType::THREAD;
        $post->creation = time();
        $post->save();

        $this->id = $post->id;

        return true;
    }

    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("threads/view/" . $this->id);
    }
}
