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
use Teca\Vote\Vote;
use Teca\Tag\Tag;

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

        $threadIds = array_unique(array_column($threads, 'id'));
        $tagIds = array_unique(explode(",", implode(",", array_column($threads, "tags"))));

        $answers = $post->findAllWhere("thread IN (?) AND type = ?", [$threadIds, PostType::ANSWER]);

        $vote = new Vote();
        $vote->setDb($this->di->get("dbqb"));
        $votes = $vote->findAllWhere("post IN (?)", [$threadIds]);

        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $tags = $tag->findAllWhere("id IN (?)", [$tagIds]);

        foreach ($threads as $thread) {
            $id = $thread->author;
            $thread->answerCount = 0;
            $thread->voteCount = 0;
            $thread->tagValues = [];
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

            foreach ($votes as $vote) {
                if (intval($vote->post) === intval($thread->id)) {
                    $thread->voteCount += intval($vote->value);
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
        $thread->answerCount = 0;
        $thread->voteCount = 0;

        $vote = new Vote();
        $vote->setDb($this->di->get("dbqb"));
        $votes = $vote->findAllWhere("post = ?", $thread->id);
        foreach ($votes as $vote) {
            $thread->voteCount += intval($vote->value);
        }

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
        $creationDates = array_column($answersAndComments, 'creation');
        array_multisort($creationDates, SORT_ASC, $answersAndComments);

        $authors = array_unique(array_column($answersAndComments, 'author'));
        $postIds = array_unique(array_column($answersAndComments, 'id'));
        $users = $user->findAllWhere("id IN (?)", [$authors]);
        $votes = $vote->findAllWhere("post IN (?)", [$postIds]);

        $answers = [];
        foreach ($answersAndComments as $post) {
            $id = $post->author;
            $post->voteCount = 0;
            foreach ($users as $author) {
                if (intval($author->id) === intval($id)) {
                    $post->authorName = $author->name;
                    $user = new User();
                    $user->avatar = $author->avatar;
                    $post->authorAvatar = $user->gravatar();
                }
            }

            foreach ($votes as $vote) {
                if (intval($vote->post) === intval($post->id)) {
                    $post->voteCount += intval($vote->value);
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
