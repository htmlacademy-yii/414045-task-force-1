<?php

use Components\Constants\UserConstants;
use Components\Users\UserService;

$rating = UserService::getCountRatingStars($model->rating)
?>

<div class="feedback-card__top">
    <div class="user__search-icon">
        <a href="/users/view/<?= $model->id ?>"><img src="<?= $model->avatar_src ?? UserConstants::USER_DEFAULT_AVATAR_SRC ?>"
                                                     width="65" height="65"></a>
        <span><?= count($model->tasks) ?> заданий</span>
        <span><?= count($model->reviews) ?> отзывов</span>
    </div>
    <div class="feedback-card__top--name user__search-card">
        <p class="link-name">
            <a href="/users/view/<?= $model->id ?>" class="link-regular"><?= $model->name ?></a>
        </p>
        <?php for ($i = 0; $i < 5; $i++): ?>
            <span class="<?= ($rating <= $i) ? 'star-disabled' : '' ?>"></span>
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

