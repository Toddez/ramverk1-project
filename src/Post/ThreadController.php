<?php

namespace Teca\Post;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class ThreadController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexAction() : object
    {
        $page = $this->di->get("page");

        $page->add("post/threads", [
        ]);

        return $page->render([
            "title" => "Samtliga Frågor",
        ]);
    }
}
