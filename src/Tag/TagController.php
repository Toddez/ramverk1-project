<?php

namespace Teca\Tag;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Teca\Post\Post;
use Teca\Post\PostType;
use Teca\Tag\Tag;

class TagController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexAction() : object
    {
        $page = $this->di->get("page");

        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $tags = $tag->findAll();

        $tagIds = array_column($tags, "id");
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

        $page->add("tag/all", [
            "tags" => $tags,
        ]);

        return $page->render([
            "title" => "Samtliga taggar",
        ]);
    }

    public function viewAction($tagId) : object
    {
        $page = $this->di->get("page");

        $page->add("tag/threads", [

        ]);

        return $page->render([
            "title" => "Specifika taggar",
        ]);
    }
}
