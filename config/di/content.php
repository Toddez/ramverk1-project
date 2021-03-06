<?php

return [
    "services" => [
        "content" => [
            "shared" => true,
            "callback" => function () {
                $content = new \Anax\Content\FileBasedContent();
                $content->setDI($this);

                $cfg = $this->get("configuration");
                $config = $cfg->load("content.php");
                $config = $config["config"] ?? null;
                $file = $config["file"] ?? null;

                $content->configure($config);

                return $content;
            }
        ],
    ],
];
