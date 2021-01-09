<?php
$user = new \Teca\User\User();
$authorized = $user->authorized($di);
$showMark = $user->id === $thread->author && $thread->hasAnswer($di) === false;

function comments($parent)
{
    ?>
        <div class="comments">
            <?php foreach ($parent->comments as $comment) : ?>
            <div class="comment">
                <div class="left">
                    <div class="votes">
                        <a class="upvote" href="../vote/<?= $comment->thread ?>/<?= $comment->id ?>/1">&#x25B2;</a>
                        <span class="number"><?= $comment->score ?></span>
                        <a class="downvote" href="../vote/<?= $comment->thread ?>/<?= $comment->id ?>/-1">&#x25BC;</a>
                    </div>
                </div>
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
        <div class="score">
            <a class="upvote" href="../vote/<?= $thread->id ?>/<?= $thread->id ?>/1">&#x25B2;</a>
            <span class="number"><?= $thread->score ?></span>
            <a class="downvote" href="../vote/<?= $thread->id ?>/<?= $thread->id ?>/-1">&#x25BC;</a>
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

<div class="sorting">
    <div>Sorterar svar efter: <?= $sort === "creation" ? "Datum" : "Rank" ?></div>
    <div>
        Ändra sortering till:
        <a href="../sortby/<?= $thread->id ?>/creation">Datum</a> |
        <a href="../sortby/<?= $thread->id ?>/score">Rank</a>
    </div>
</div>

<div class="answers">
    <?php foreach ($answers as $answer) : ?>
    <div class="answer thread inspect <?= $answer->answer ? 'marked' : '' ?>">
        <div class="left">
            <div class="score">
                <a class="upvote" href="../vote/<?= $thread->id ?>/<?= $answer->id ?>/1">&#x25B2;</a>
                <span class="number"><?= $answer->score ?></span>
                <a class="downvote" href="../vote/<?= $thread->id ?>/<?= $answer->id ?>/-1">&#x25BC;</a>
            </div>
        </div>
        <div class="right">
            <div class="content"><?= $answer->content ?></div>
            <div class="details">
                <?php if ($showMark) { ?>
                <a class="mark" href="../mark/<?= $thread->id ?>/<?= $answer->id ?>">Markera som svar</a>
                <?php } ?>
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
