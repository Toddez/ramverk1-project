<?php

?>

<div class="allTags">
    <?php foreach ($tags as $tag) : ?>
    <div class="tag">
        <div class="count"><?= $tag->useCount ?></div>
        <a class="value" href="tags/view/<?= $tag->id ?>"><?= htmlentities($tag->value) ?></a>
    </div>
    <?php endforeach; ?>
</div>
