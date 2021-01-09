<?php

namespace Teca\Post;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\TextFilter\TextFilter;
use Teca\Post\HTMLForm\NewThreadForm;
use Teca\Post\HTMLForm\AnswerForm;
use Teca\Post\HTMLForm\CommentForm;
use Teca\User\User;
use Teca\Post\Post;
use Teca\Post\PostType;
use Teca\Tag\Tag;
use Teca\Vote\Vote;

class ThreadController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function getThreads() : array
    {
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $threads = $post->findAllWhere("type = ?", PostType::THREAD);

        $authors = array_unique(array_column($threads, 'author'));
        $user = new user();
        $user->setDb($this->di->get("dbqb"));
        $users = $user->findAllWhere("id IN (?)", [$authors]);

        $threadIds = array_unique(array_column($threads, 'id'));
        $tagIds = array_unique(explode(",", implode(",", array_column($threads, "tags"))));

        $answers = $post->findAllWhere("thread IN (?) AND type = ?", [$threadIds, PostType::ANSWER]);

        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $tags = $tag->findAllWhere("id IN (?)", [$tagIds]);

        $filter = new TextFilter();
        foreach ($threads as $thread) {
            $id = $thread->author;
            $thread->answerCount = 0;
            $thread->score = $thread->score($this->di);
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

        return $threads;
    }

    public function indexAction() : object
    {
        $page = $this->di->get("page");

        $threads = $this->getThreads();

        $page->add("post/threads", [
            "threads" => $threads,
            "prefix" => "",
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
        $filter = new TextFilter();

        $session = $this->di->get("session");
        $sortBy = $session->get("sortby", 'creation');

        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $thread = $post->findWhere("id = ?", intval($threadId));
        $thread->comments = [];
        $thread->answerCount = 0;
        $thread->score = $thread->score($this->di);
        $thread->content = $filter->parse($thread->content, ["markdown"])->text;

        $tagIds = explode(",", $thread->tags);

        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $tags = $tag->findAllWhere("id IN (?)", [$tagIds]);

        $thread->tagValues = $tags;

        $user = new user();
        $user->setDb($this->di->get("dbqb"));
        $author = $user->findWhere("id = ?", $thread->author);
        $thread->authorName = $author->name;
        $thread->authorAvatar = $author->gravatar();

        $answersAndComments = $post->findAllWhere("thread = ?", intval($threadId));
        $authors = array_unique(array_column($answersAndComments, 'author'));
        $users = $user->findAllWhere("id IN (?)", [$authors]);

        foreach ($answersAndComments as $post) {
            $post->score = $post->score($this->di);
        }

        $sortColumn = array_column($answersAndComments, 'creation');
        array_multisort($sortColumn, SORT_ASC, $answersAndComments);

        $answers = [];
        foreach ($answersAndComments as $post) {
            $id = $post->author;
            $post->content = $filter->parse($post->content, ["markdown"])->text;
            foreach ($users as $author) {
                if (intval($author->id) === intval($id)) {
                    $post->authorName = $author->name;
                    $user = new User();
                    $user->avatar = $author->avatar;
                    $post->authorAvatar = $user->gravatar();
                }
            }

            if (intval($post->type) === PostType::ANSWER) {
                $post->comments = [];
                $answers[] = $post;
                $thread->answerCount++;
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

        $sortColumn = array_column($answers, $sortBy);
        array_multisort($sortColumn, $sortBy === 'score' ? SORT_DESC : SORT_ASC, $answers);

        foreach ($answers as $answer) {
            $sortColumn = array_column($answer->comments, $sortBy);
            array_multisort($sortColumn, $sortBy === 'score' ? SORT_DESC : SORT_ASC, $answer->comments);
        }

        $page = $this->di->get("page");

        $page->add("post/thread", [
            "thread" => $thread,
            "answers" => $answers,
            "sort" => $sortBy,
        ]);

        return $page->render([
            "title" => "Ny fråga",
        ]);
    }

    public function voteAction($threadId, $postId, $value) : object
    {
        $user = new User();
        $user->currentUser($this->di);

        if (!$user->id) {
            return $this->di->get("response")->redirect("threads/view/" . $threadId);
        }

        $vote = new Vote();
        $vote->setDb($this->di->get("dbqb"));
        $currentVote = $vote->findWhere("user = ? AND post = ?", [$user->id, $postId]);

        if ($currentVote->value === null) {
            $vote->value = $value;
            $vote->user = $user->id;
            $vote->post = $postId;
        } else if ($currentVote->value === $value) {
            $vote->value = 0;
        } else {
            $vote->value = $value;
        }

        $vote->save();

        $this->di->get("response")->redirect("threads/view/" . $threadId);
    }

    public function markAction($threadId, $postId) : object
    {
        $user = new User();
        $user->currentUser($this->di);

        if (!$user->id) {
            return $this->di->get("response")->redirect("threads/view/" . $threadId);
        }

        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $answer = $post->findWhere("thread = ? AND id = ? AND type = ?", [$threadId, $postId, PostType::ANSWER]);

        if ($answer->author !== $user->id) {
            return $this->di->get("response")->redirect("threads/view/" . $threadId);
        }

        if ($answer->id) {
            $answer->answer = true;
            $answer->save();
        }

        return $this->di->get("response")->redirect("threads/view/" . $threadId);
    }

    public function sortbyAction($threadId, $value) : object
    {
        $session = $this->di->get("session");
        $session->set("sortby", $value);
        return $this->di->get("response")->redirect("threads/view/" . $threadId);
    }
}
