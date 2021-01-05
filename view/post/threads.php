<?php
$user = new \Teca\User\User();
$authorized = $user->authorized($di);

if (isset($text)) { ?>
    <div><?= htmlentities($text) ?></div>
<?php } ?>

<div class="threads">
    <?php if ($authorized && !isset($text)) { ?>
        <a class="newThread" href="threads/new">Ny fråga</a>
    <?php } ?>
    <div>Samtliga frågor:</div>
    <?php foreach ($threads as $thread) : ?>
    <div class="thread">
        <div class="left">
            <div class="score">
                <span class="number"><?= $thread->voteCount ?></span>
                <span>röster</span>
            </div>
            <div class="answers">
                <span class="number"><?= $thread->answerCount ?></span>
                <span>svar</span>
            </div>
        </div>
        <div class="right">
            <div class="content">
                <a class="title" href="<?= $prefix ?>threads/view/<?= $thread->id ?>">Q: <?= htmlentities($thread->title) ?></a>
                <div class="snippet"><?= htmlentities($thread->content) ?></div>
            </div>
            <div class="details">
                <div class="tags">
                    <?php foreach ($thread->tagValues as $tag) : ?>
                    <a class="tag" href="<?= $prefix ?>tags/view/<?= $tag->id ?>"><?= htmlentities($tag->value) ?></a>
                    <?php endforeach; ?>
                </div>
                <div class="date"><?= date("H:i F j 'y", $thread->creation) ?></div>
                <a class="name" href="<?= $prefix ?>user/view/<?= $thread->author ?>"><?= htmlentities($thread->authorName) ?></a>
                <img class="avatar" src="<?= $thread->authorAvatar ?>">
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
