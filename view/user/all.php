<?php

?>


<div class="allUsers">
    <div>Samtliga användare:</div>
    <?php foreach ($users as $user) : ?>
    <div class="user">
        <div class="count"><?= $user->authorCount ?></div>
        <a class="value" href="<?= $prefix ?>user/view/<?= $user->id ?>"><?= htmlentities($user->name) ?></a>
        <img class="avatar" src="<?= $user->gravatar() ?>">
    </div>
    <?php endforeach; ?>
</div>
