<?php

return [
    "services" => [
        "db" => [
            "shared" => true,
            "callback" => function () {
                $db = new \Anax\Database\Database();

                $cfg = $this->get("configuration");
                $config = $cfg->load("database.php");
                $config["config"]["dsn"] = "sqlite:" . ANAX_INSTALL_PATH . "/data/test_db.sqlite";

                $connection = $config["config"] ?? [];
                $db->setOptions($connection);

                return $db;
            }
        ],
    ],
];
