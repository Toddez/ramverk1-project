<?php

?>

<div>Samtliga användare:</div>

<div class="allUsers">
    <?php foreach ($users as $user) : ?>
    <div class="user">
        <div class="count"><?= $user->authorCount ?></div>
        <a class="value" href="../user/view/<?= $user->id ?>"><?= htmlentities($user->name) ?></a>
        <img class="avatar" src="<?= $user->gravatar() ?>">
    </div>
    <?php endforeach; ?>
</div>
