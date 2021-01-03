<?php

namespace Anax\View;

/**
 * Template file to render a view.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

$authorized = \Teca\User\User::authorized($di);
$auth["items"][0]["text"] = \Teca\User\User::currentUser($di)->name;

$navbar = new \Anax\Navigation\Navbar();
$navbar->setDI($di);
$html = $navbar->createMenuWithSubMenus($authorized ? $auth : $noauth);

$classes = "rm-navbar " . ( $class ?? null);



?><!-- menu wrapper -->
<div <?= classList($classes) ?>>
    <!-- main menu -->
    <?= $html ?>
</div>
