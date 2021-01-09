<?php
$user = new \Teca\User\User();
$authorized = $user->authorized($di);

function comments($parent)
{
    ?>
        <div class="comments">
            <?php foreach ($parent->comments as $comment) : ?>
            <div class="comment">
                <div class="right">
                    <span class="content"><?= $comment->content ?> - </span>
                    <a class="name" href="user/view/<?= $comment->author ?>"><?= $comment->authorName ?></a>
                    <span class="date"><?= date("H:i F j 'y", $comment->creation) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php
}
?>

<div class="thread inspect">
    <div class="left">
        <div class="answers">
            <span class="number"><?= $thread->answerCount ?></span>
            <span>svar</span>
        </div>
    </div>
    <div class="right">
        <a class="title" href="<?= $thread->id ?>">Q: <?= $thread->title ?></a>
        <div class="content">
            <?= $thread->content ?>
        </div>
        <div class="tags">
            <?php foreach ($thread->tagValues as $tag) : ?>
            <a class="tag" href="../../tags/view/<?= $tag->id ?>"><?= $tag->value ?></a>
            <?php endforeach; ?>
        </div>
        <div class="details">
            <div class="date"><?= date("H:i F j 'y", $thread->creation) ?></div>
            <a class="name" href="../../user/view/<?= $thread->author ?>"><?= $thread->authorName ?></a>
            <img class="avatar" src="<?= $thread->authorAvatar ?>">
        </div>
        <?= comments($thread) ?>
        <a class="addComment" href="../comment/<?= $thread->id ?>/<?= $thread->id ?>">Lägg till en kommentar</a>
    </div>
</div>

<div class="answers">
    <?php foreach ($answers as $answer) : ?>
    <div class="answer thread inspect">
        <div class="right">
            <div class="content"><?= $answer->content ?></div>
            <div class="details">
                <div class="date"><?= date("H:i F j 'y", $answer->creation) ?></div>
                <a class="name" href="../../user/view/<?= $answer->author ?>"><?= $answer->authorName ?></a>
                <img class="avatar" src="<?= $answer->authorAvatar ?>">
            </div>
            <?= comments($answer) ?>
            <a class="addComment" href="../comment/<?= $thread->id ?>/<?= $answer->id ?>">Lägg till en kommentar</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<a class="addAnswer" href="../answer/<?= $thread->id ?>">Svara på frågan</a>
