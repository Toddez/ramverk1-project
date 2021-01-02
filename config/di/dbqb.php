<?php

return [
    "services" => [
        "dbqb" => [
            "shared" => true,
            "callback" => function () {
                $db = new \Anax\DatabaseQueryBuilder\DatabaseQueryBuilder();

                $cfg = $this->get("configuration");
                $config = $cfg->load("database");

                $connection = $config["config"] ?? [];
                $db->setOptions($connection);
                $db->setDefaultsFromConfiguration();

                return $db;
            }
        ],
    ],
];
