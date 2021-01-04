<?php
$user = new \Teca\User\User();
$authorized = $user->authorized($di);

function comments($parent)
{
    ?>
        <div class="comments">
            <?php foreach ($parent->comments as $comment) : ?>
            <div class="comment">
                <div class="left">
                    <span class="votes">tmp</span>
                </div>
                <div class="right">
                    <span class="content"><?= htmlentities($comment->content) ?> - </span>
                    <a class="name" href="user/view/<?= $comment->author ?>"><?= htmlentities($comment->authorName) ?></a>
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
        <div class="score">
            <span class="number">tmp</span>
            <span>röster</span>
        </div>
        <div class="answers">
            <span class="number">tmp</span>
            <span>svar</span>
        </div>
    </div>
    <div class="right">
        <a class="title" href="<?= $thread->id ?>">Q: <?= htmlentities($thread->title) ?></a>
        <div class="details">
            <div class="date"><?= date("H:i F j 'y", $thread->creation) ?></div>
            <a class="name" href="../../user/view/<?= $thread->author ?>"><?= htmlentities($thread->authorName) ?></a>
        </div>
        <div class="content">
            <?= htmlentities($thread->content) ?>
        </div>
        <div class="tags">
            <a class="tag">tmp</a>
            <a class="tag">tmp</a>
        </div>
        <?= comments($thread) ?>
        <a class="addComment" href="../comment/<?= $thread->id ?>/<?= $thread->id ?>">Lägg till en kommentar</a>
    </div>
</div>

<div class="answers">
    <?php foreach ($answers as $answer) : ?>
    <div class="answer thread inspect">
        <div class="left">
            <div class="score">
                <span class="number">tmp</span>
                <span>röster</span>
            </div>
        </div>
        <div class="right">
            <div class="content"><?= htmlentities($answer->content) ?></div>
            <div class="details">
                <div class="date"><?= date("H:i F j 'y", $answer->creation) ?></div>
                <a class="name" href="../../user/view/<?= $answer->author ?>"><?= htmlentities($answer->authorName) ?></a>
            </div>
            <?= comments($answer) ?>
            <a class="addComment" href="../comment/<?= $thread->id ?>/<?= $answer->id ?>">Lägg till en kommentar</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<a class="addAnswer" href="../answer/<?= $thread->id ?>">Svara på frågan</a>
