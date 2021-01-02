<?php

return [

    "basePath" => ANAX_INSTALL_PATH . "/content",
    "ignoreCache" => true,

    "textfilter-frontmatter" => [
        // "jsonfrontmatter",
        // "yamlfrontmatter",
        "frontmatter",
    ],

    "textfilter-title" => [
        "markdown",
        "titlefromheader",
    ],

    "textfilter" => [
        "shortcode",
        "markdown",
        "titlefromheader",
        "anchor4Header",
    ],

    "template" => "anax/v2/article/default",

    "revision-history" => [
        "start" => "\n\n\n" . t("Revision history") . " {#revision}\n-------------\n\n<span class=\"revision-history\">\n",
        "end"   => "</span>\n",
        "class" => "revision-history",
    ],

    "pattern"   => "*.md",
    "meta"      => ".meta.md",
    "author"    => "#author/([^\.]+)#",
    "category"  => "#kategori/([^\.]+)#",
    "pagination" => "sida",
];
