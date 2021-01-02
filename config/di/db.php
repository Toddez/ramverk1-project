<?php

return [
    "services" => [
        "db" => [
            "shared" => true,
            "callback" => function () {
                $db = new \Anax\Database\Database();

                $cfg = $this->get("configuration");
                $config = $cfg->load("database");

                $connection = $config["config"] ?? [];
                $db->setOptions($connection);

                return $db;
            }
        ],
    ],
];
