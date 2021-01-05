<?php

return [
    "services" => [
        "configuration" => [
            "shared" => true,
            "callback" => function () {
                $config = new \Anax\Configure\Configuration();
                $dirs = require __DIR__ . "/../configuration.php";
                $config->setBaseDirectories($dirs);
                return $config;
            }
        ],
    ],
];
