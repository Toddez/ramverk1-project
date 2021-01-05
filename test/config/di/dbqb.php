<?php

return [
    "services" => [
        "dbqb" => [
            "shared" => true,
            "callback" => function () {
                $db = new \Anax\DatabaseQueryBuilder\DatabaseQueryBuilder();

                $cfg = $this->get("configuration");
                $config = $cfg->load("database.php");
                $config["config"]["dsn"] = "sqlite:" . ANAX_INSTALL_PATH . "/data/test_db.sqlite";

                $connection = $config["config"] ?? [];
                $db->setOptions($connection);
                $db->setDefaultsFromConfiguration();

                return $db;
            }
        ],
    ],
];
