<?php

return [
    "services" => [
        "session" => [
            "active" => defined("ANAX_WITH_SESSION") && ANAX_WITH_SESSION, // true|false
            "shared" => true,
            "callback" => function () {
                $session = new \Anax\Session\Session();

                $cfg = $this->get("configuration");
                $config = $cfg->load("session");

                $name = $config["config"]["name"] ?? null;
                if (is_string($name)) {
                    $session->name($name);
                }

                $session->start();

                return $session;
            }
        ],
    ],
];
