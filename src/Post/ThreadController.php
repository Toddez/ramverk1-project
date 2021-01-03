<?php

namespace Teca\Post;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Teca\Post\HTMLForm\NewThreadForm;
use Teca\User\User;
use Teca\Post\Post;
use Teca\Post\PostType;

class ThreadController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexAction() : object
    {
        $page = $this->di->get("page");

        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $threads = $post->findAllWhere("type = ?", PostType::THREAD);

        $authors = array_unique(array_column($threads, 'author'));
        $user = new user();
        $user->setDb($this->di->get("dbqb"));
        $users = $user->findAllWhere("id IN (?)", [$authors]);

        foreach ($threads as $thread) {
            $id = intval($thread->author) - 1;
            $thread->authorName = $users[$id]->name;
        }

        $creationDates = array_column($threads, 'creation');
        array_multisort($creationDates, SORT_DESC, $threads);

        $page->add("post/threads", [
            "threads" => $threads,
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
