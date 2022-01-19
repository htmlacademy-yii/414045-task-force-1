<?php

/**
 * @var User $model
 * @var string $lastActivity ;
 */

use Components\Constants\UserConstants;
use Components\Routes\Route;
use Components\Users\UserService;
use frontend\models\User;

$rating = (new UserService())->getCountRatingStars($model->rating);
$lastActivity = (new UserService())->getLastActivity($model);
?>

<div class="feedback-card__top">
    <div class="user__search-icon">
        <a href="/users/view/<?= $model->id ?>"><img
                    src="<?= $model->avatar_src ?? UserConstants::USER_DEFAULT_AVATAR_SRC ?>"
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
    <span class="new-task__time"><?= $lastActivity ?></span>
</div>
<div class="link-specialization user__search-link--bottom">
    <?php foreach ($model->specialties as $specialty) : ?>
        <a href="<?= Route::getTasks($specialty->id) ?>"
           class="link-regular"><?= $specialty->title ?></a>
    <?php endforeach; ?>
</div>

