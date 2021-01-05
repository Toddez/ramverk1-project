<?php

namespace Teca\Tag;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Teca\Post\Post;
use Teca\Post\PostType;
use Teca\Tag\Tag;
use Teca\Vote\Vote;
use Teca\User\User;

class TagController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function getTags() : array
    {
        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $tags = $tag->findAll();

        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $posts = $post->findAll();

        foreach ($tags as $tag) {
            $tag->useCount = 0;
            foreach ($posts as $post) {
                if (in_array($tag->id, explode(",", $post->tags))) {
                    $tag->useCount++;
                }
            }
        }

        $useCounts = array_column($tags, 'useCount');
        array_multisort($useCounts, SORT_DESC, $tags);

        return $tags;
    }

    public function indexAction() : object
    {
        $page = $this->di->get("page");

        $tags = $this->getTags();

        $page->add("tag/all", [
            "tags" => $tags,
            "prefix" => "",
        ]);

        return $page->render([
            "title" => "Samtliga taggar",
        ]);
    }

    public function viewAction($tagId) : object
    {
        $page = $this->di->get("page");

        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $targetTag = $tag->findWhere("id = ?", $tagId);

        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $posts = $post->findAll();

        $threads = [];
        foreach ($posts as $post) {
            if (in_array($targetTag->id, explode(",", $post->tags))) {
                $threads[] = $post;
            }
        }

        $threadIds = array_column($posts, "id");
        $tagIds = array_unique(explode(",", implode(",", array_column($threads, "tags"))));

        $vote = new Vote();
        $vote->setDb($this->di->get("dbqb"));
        $votes = $vote->findAllWhere("post IN (?)", [$threadIds]);

        $authors = array_unique(array_column($threads, 'author'));
        $user = new user();
        $user->setDb($this->di->get("dbqb"));
        $users = $user->findAllWhere("id IN (?)", [$authors]);

        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $answers = $post->findAllWhere("thread IN (?) AND type = ?", [$threadIds, PostType::ANSWER]);

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

        $creationDates = array_column($posts, 'creation');
        array_multisort($creationDates, SORT_DESC, $posts);

        $page->add("post/threads", [
            "threads" => $threads,
            "text" => "Alla frågor med taggen: " . $targetTag->value,
            "prefix" => "../../",
        ]);

        return $page->render([
            "title" => "Specifika taggar",
        ]);
    }
}
