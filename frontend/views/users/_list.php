<?php

?>

<div class="feedback-card__top">
    <div class="user__search-icon">
        <a href="user.html"><img src="<?= $model->avatar_src ?? './img/user.png' ?>"
                                 width="65" height="65"></a>
        <span><?= count($model->tasks) ?> заданий</span>
        <span><?= count($model->reviews) ?> отзывов</span>
    </div>
    <div class="feedback-card__top--name user__search-card">
        <p class="link-name"><a href="user.html"
                                class="link-regular"><?= $model->name ?></a>
        </p>
        <?php for ($i = 1; $i <= 5; $i++) : ?>
            <?php if ($model->rating > $i * 100) : ?>
                <span></span>
            <?php else: ?>
                <span class="star-disabled"></span>
            <?php endif; ?>
        <?php endfor; ?>
        <b><?= $model->rating / 100 ?></b>
        <p class="user__search-content">
            <?= $model->about ?>
        </p>
    </div>
    <span class="new-task__time">Был на сайте 25 минут назад</span>
</div>
<div class="link-specialization user__search-link--bottom">
    <?php foreach ($model->categories as $category) : ?>
        <a href="browse.html"
           class="link-regular"><?= $category->title ?></a>
    <?php endforeach; ?>
</div>

