<?php

namespace Teca\Post;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Teca\Post\HTMLForm\NewThreadForm;
use Teca\Post\HTMLForm\AnswerForm;
use Teca\Post\HTMLForm\CommentForm;
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
            $id = $thread->author;
            foreach ($users as $author) {
                if ($author->id === $id) {
                    $thread->authorName = $author->name;
                }
            }
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

    public function answerAction($thread) : object
    {
        $user = new User();
        if (!$user->authorized($this->di)) {
            $this->di->get("response")->redirect("user/login")->send();
        }

        $page = $this->di->get("page");
        $form = new AnswerForm($this->di, $thread);
        $form->check();

        $page->add("post/newthread", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Svara",
        ]);
    }

    public function commentAction($thread, $parent) : object
    {
        $user = new User();
        if (!$user->authorized($this->di)) {
            $this->di->get("response")->redirect("user/login")->send();
        }

        $page = $this->di->get("page");
        $form = new CommentForm($this->di, $thread, $parent);
        $form->check();

        $page->add("post/newthread", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Kommentera",
        ]);
    }

    public function viewAction($threadId) : object
    {
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $thread = $post->findWhere("id = ?", intval($threadId));
        $thread->comments = [];

        $user = new user();
        $user->setDb($this->di->get("dbqb"));
        $author = $user->findWhere("id = ?", $thread->author);
        $thread->authorName = $author->name;

        $answersAndComments = $post->findAllWhere("thread = ?", intval($threadId));
        $creationDates = array_column($answersAndComments, 'creation');
        array_multisort($creationDates, SORT_ASC, $answersAndComments);

        $authors = array_unique(array_column($answersAndComments, 'author'));
        $users = $user->findAllWhere("id IN (?)", [$authors]);

        $answers = [];
        foreach ($answersAndComments as $post) {
            $id = $post->author;
            foreach ($users as $author) {
                if ($author->id === $id) {
                    $post->authorName = $author->name;
                }
            }

            if (intval($post->type) === PostType::ANSWER) {
                $post->comments = [];
                $answers[] = $post;
            }

            if (intval($post->type) === PostType::COMMENT) {
                if (intval($post->parent) === intval($thread->id)) {
                    $thread->comments[] = $post;
                }
 
                foreach ($answers as $answer) {
                    if (intval($post->parent) === intval($answer->id)) {
                        $answer->comments[] = $post;
                    }
                }
            }
        }

        $page = $this->di->get("page");

        $page->add("post/thread", [
            "thread" => $thread,
            "answers" => $answers,
        ]);

        return $page->render([
            "title" => "Ny fråga",
        ]);
    }
}
