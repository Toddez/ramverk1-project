<?php

namespace Teca\Post\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Teca\User\User;
use Teca\Post\Post;
use Teca\Post\PostType;

class AnswerForm extends FormModel
{
    private $thread;

    public function __construct(ContainerInterface $di, $thread)
    {
        $this->thread = $thread;

        parent::__construct($di);
        $this->form->create(
            [
                "id" => "ThreadForm",
                "legend" => "Svara",
            ],
            [
                "content" => [
                    "type" => "textarea",
                    "label" => "Svar",
                    "validation" => [
                        "not_empty"
                    ],
                    "escape-values" => false
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Svara på frågan",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }

    public function callbackSubmit() : bool
    {
        $content = $this->form->value("content");

        $user = new User();
        $user->currentUser($this->di);

        if (!$user->id) {
            return false;
        }

        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $post->author = $user->id;
        $post->content = $content;
        $post->type = PostType::ANSWER;
        $post->thread = $this->thread;
        $post->creation = time();
        $post->save();

        return true;
    }

    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("threads/view/" . $this->thread);
    }
}
