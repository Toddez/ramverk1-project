<?php

define("ANAX_DEVELOPMENT", true);
//define("ANAX_PRODUCTION", true);

define("ANAX_WITH_SESSION", true);

error_reporting(-1); // Report all type of errors

if (constant("ANAX_DEVELOPMENT")) {
    ini_set("display_errors", 1);
} elseif (constant("ANAX_PRODUCTION")) {
    ini_set("display_errors", 0);
    ini_set("log_errors", 1);
    ini_set("error_log", ANAX_INSTALL_PATH . "/log/error_log");
}

set_exception_handler(function ($e) {
    echo "<p>Anax: Uncaught exception:</p><p>Line "
        . $e->getLine()
        . " in file "
        . $e->getFile()
        . "</p><p><code>"
        . get_class($e)
        . "</code></p><p>"
        . $e->getMessage()
        . "</p><p>Code: "
        . $e->getCode()
        . "</p><pre>"
        . $e->getTraceAsString()
        . "</pre>";
});
