<?php
return [
    "layout" => [
        "region" => "layout",
        "template" => "anax/v2/layout/dbwebb_se",
        "data" => [
            "baseTitle" => " | projekt",
            "bodyClass" => null,
            "favicon" => "img/logo.svg",
            "htmlClass" => null,
            "lang" => "sv",
            "stylesheets" => [
                "css/normalize.css",
                "css/base.css",
                "css/layout.css",
                "css/style.css",
            ],
        ],
    ],

    "views" => [
        [
            "region" => "header-col-1",
            "template" => "anax/v2/header/site_logo_text",
            "data" => [
                "homeLink"      => "",
                "siteLogoText"  => "ramverk1 projekt",
                "siteLogoTextIcon" => "img/logo.svg",
                "siteLogoTextIconAlt" => "logo",
            ],
        ],
        [
            "region" => "header-col-2",
            "template" => "anax/v2/navbar/navbar_submenus",
            "data" => [
                "navbarConfig" => require __DIR__ . "/navbar/left-header.php",
            ],
        ],
        [
            "region" => "header-col-3",
            "template" => "navbar",
            "data" => [
                "auth" => require __DIR__ . "/navbar/right-header-auth.php",
                "noauth" => require __DIR__ . "/navbar/right-header.php",
            ],
        ],
        [
            "region" => "footer",
            "template" => "anax/v2/block/default",
            "data" => [
                "class"  => "site-footer",
                "contentRoute" => "block/footer-desc",
            ],
            "sort" => 1
        ],
        [
            "region" => "footer",
            "template" => "anax/v2/block/default",
            "data" => [
                "class"  => "site-footer",
                "contentRoute" => "block/footer",
            ],
            "sort" => 2
        ],
    ],
];
