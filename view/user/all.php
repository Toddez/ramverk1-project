<?php

?>


<div class="allUsers">
    <div>Samtliga användare:</div>
    <?php foreach ($users as $user) : ?>
    <div class="user">
        <div class="count"><?= $user->score($di) ?></div>
        <a class="value" href="<?= $prefix ?>user/view/<?= $user->id ?>"><?= $user->name ?></a>
        <img class="avatar" src="<?= $user->gravatar() ?>">
    </div>
    <?php endforeach; ?>
</div>
