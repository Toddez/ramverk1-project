<?php

namespace Anax\View;

/**
 * Template file to render a view.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

$user = new \Teca\User\User();
$authorized = $user->authorized($di);
$user->currentUser($di);
$auth["items"][0]["text"] = $user->name . "<img class='avatar' src='". $user->gravatar() . "'>";

$navbar = new \Anax\Navigation\Navbar();
$navbar->setDI($di);
$html = $navbar->createMenuWithSubMenus($authorized ? $auth : $noauth);

$classes = "rm-navbar " . ( $class ?? null);



?><!-- menu wrapper -->
<div <?= classList($classes) ?>>
    <!-- main menu -->
    <?= $html ?>
</div>
