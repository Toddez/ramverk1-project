<?php

return [
    "services" => [
        "request" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Anax\Request\Request();
                $obj->init();
                return $obj;
            }
        ],
    ],
];
