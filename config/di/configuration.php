<?php

return [
    "services" => [
        "configuration" => [
            "shared" => true,
            "callback" => function () {
                $config = new \Anax\Configure\Configuration();
                $dirs = require ANAX_INSTALL_PATH . "/config/configuration.php";
                $config->setBaseDirectories($dirs);
                return $config;
            }
        ],
    ],
];
