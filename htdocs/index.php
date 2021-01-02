<?php

define("ANAX_INSTALL_PATH", realpath(__DIR__ . "/.."));

require ANAX_INSTALL_PATH . "/config/commons.php";

require ANAX_INSTALL_PATH . "/vendor/autoload.php";

$di = new Anax\DI\DIFactoryConfig();
$di->loadServices(ANAX_INSTALL_PATH . "/config/di");

$di->get("response")->send(
    $di->get("router")->handle(
        $di->get("request")->getRoute(),
        $di->get("request")->getMethod()
    )
);
