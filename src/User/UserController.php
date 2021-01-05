<?php

namespace Teca\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\TextFilter\TextFilter;
use Teca\User\HTMLForm\ProfileForm;
use Teca\User\HTMLForm\LoginForm;
use Teca\User\HTMLForm\RegisterForm;
use Teca\User\User;
use Teca\Post\Post;
use Teca\Post\PostType;
use Teca\Tag\Tag;

class UserController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexAction() : object
    {
        $user = new User();
        if (!$user->authorized($this->di)) {
            $this->di->get("response")->redirect("user/login")->send();
        }

        $page = $this->di->get("page");
        $form = new ProfileForm($this->di);
        $form->check();

        $page->add("user/profile", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Profil",
        ]);
    }

    public function getUsers() : array
    {
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $users = $user->findAll();

        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $posts = $post->findAll();

        foreach ($users as $user) {
            $user->authorCount = 0;
            foreach ($posts as $post) {
                if ($user->id === $post->author) {
                    $user->authorCount++;
                }
            }
        }

        $authorCount = array_column($users, 'authorCount');
        array_multisort($authorCount, SORT_DESC, $users);

        return $users;
    }

    public function allAction() : object
    {
        $page = $this->di->get("page");

        $users = $this->getUsers();

        $page->add("user/all", [
            "users" => $users,
            "prefix" => "../",
        ]);

        return $page->render([
            "title" => "Samtliga användare",
        ]);
    }

    public function viewAction($userId) : object
    {
        $page = $this->di->get("page");

        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $threads = $post->findAllWhere("author = ? AND type = ?", [$userId, PostType::THREAD]);
        $answers = $post->findAllWhere("author = ? AND type = ?", [$userId, PostType::ANSWER]);

        $answeredThreads = $post->findAllWhere("id IN (?)", [array_column($answers, "thread")]);

        $threadIds = array_column(array_merge($threads, $answeredThreads), "id");
        $tagIds = array_unique(explode(",", implode(",", array_column(array_merge($threads, $answeredThreads), "tags"))));

        $authors = array_unique(array_column(array_merge($threads, $answeredThreads), 'author'));
        $user = new user();
        $user->setDb($this->di->get("dbqb"));
        $users = $user->findAllWhere("id IN (?)", [$authors]);

        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $answers = $post->findAllWhere("thread IN (?) AND type = ?", [$threadIds, PostType::ANSWER]);

        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $tags = $tag->findAllWhere("id IN (?)", [$tagIds]);

        $filter = new TextFilter();
        foreach (array_merge($threads, $answeredThreads) as $thread) {
            $id = $thread->author;
            $thread->answerCount = 0;
            $thread->voteCount = 0;
            $thread->tagValues = [];
            $thread->content = $filter->parse($thread->content, ["markdown"])->text;
            foreach ($users as $author) {
                if ($author->id === $id) {
                    $thread->authorName = $author->name;
                    $thread->authorAvatar = $author->gravatar();
                }
            }

            foreach ($answers as $answer) {
                if (intval($answer->thread) === intval($thread->id)) {
                    $thread->answerCount++;
                }
            }

            foreach ($tags as $tag) {
                if (in_array($tag->id, explode(",", $thread->tags))) {
                    $thread->tagValues[] = $tag;
                }
            }
        }

        $creationDates = array_column($threads, 'creation');
        array_multisort($creationDates, SORT_DESC, $threads);

        $page->add("user/threads", [
            "threads" => $threads,
            "answeredThreads" => $answeredThreads,
            "prefix" => "../../",
        ]);

        return $page->render([
            "title" => "Specifik användare",
        ]);
    }

    public function logoutAction() : object
    {
        $user = new User();
        $user->logout($this->di);
        $this->di->get("response")->redirect("user")->send();
    }

    public function loginAction() : object
    {
        $user = new User();
        if ($user->authorized($this->di)) {
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
        $user = new User();
        if ($user->authorized($this->di)) {
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
