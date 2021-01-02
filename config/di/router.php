<?php

return [
    "services" => [
        "router" => [
            "shared" => true,
            "callback" => function () {
                $router = new \Anax\Route\Router();
                $router->setDI($this);

                $cfg = $this->get("configuration");
                $config = $cfg->load("router");

                $mode = $config["config"]["mode"] ?? null;
                if (isset($mode)) {
                    $router->setMode($mode);
                } else if (defined("ANAX_PRODUCTION")) {
                    $router->setMode(\Anax\Route\Router::PRODUCTION);
                }

                $file = null;
                try {
                    $file = $config["file"] ?? null;
                    $router->addRoutes($config["config"] ?? []);
                    foreach ($config["items"] ?? [] as $routes) {
                        $file = $routes["file"];
                        $router->addRoutes($routes["config"]);
                    }
                } catch (Exception $e) {
                    throw new Exception(
                        "Configuration file: '$file'. "
                        . $e->getMessage()
                    );
                }

                return $router;
            }
        ],
    ],
];
