<?php

namespace Teca\Post\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Teca\User\User;
use Teca\Post\Post;
use Teca\Post\PostType;

class CommentForm extends FormModel
{
    private $thread;
    private $parent;

    public function __construct(ContainerInterface $di, $thread, $parent)
    {
        $this->thread = $thread;
        $this->parent = $parent;

        parent::__construct($di);
        $this->form->create(
            [
                "id" => "ThreadForm",
                "legend" => "Kommentera",
            ],
            [
                "content" => [
                    "type" => "textarea",
                    "label" => "Kommentar",
                    "validation" => [
                        "not_empty"
                    ],
                    "escape-values" => false
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Lägg till kommentar",
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
        $post->type = PostType::COMMENT;
        $post->thread = $this->thread;
        $post->parent = $this->parent;
        $post->creation = time();
        $post->save();

        return true;
    }

    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("threads/view/" . $this->thread);
    }
}
