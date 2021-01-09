<?php

?>

<div class="allTags">
    <div>Samtliga taggar:</div>
    <?php foreach ($tags as $tag) : ?>
    <div class="tag">
        <div class="count"><?= $tag->useCount ?></div>
        <a class="value" href="tags/view/<?= $tag->id ?>"><?= $tag->value ?></a>
    </div>
    <?php endforeach; ?>
</div>
