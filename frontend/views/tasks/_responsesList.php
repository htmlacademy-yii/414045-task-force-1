<?php
/**
 * @var User $model ;
 */

use Components\Constants\UserConstants;
use Components\Users\UserHelper;
use frontend\models\User;

$rating = UserHelper::getCountRatingStars($model['rating']);
?>

<div class="feedback-card__top">
    <a href="user.html"><img src="<?= $model['avatar_src'] ?? UserConstants::USER_DEFAULT_AVATAR_SRC ?>" width="55" height="55"></a>
    <div class="feedback-card__top--name">
        <p><a href="user.html" class="link-regular"><?= $model['name'] ?></a></p>
        <?php for ($i = 0; $i < 5; $i++): ?>
            <span class="<?= ($rating <= $i) ? 'star-disabled' : '' ?>"></span>
        <?php endfor; ?>
        <b><?= $rating ?></b>
    </div>
    <span class="new-task__time">25 минут назад</span>
</div>
<div class="feedback-card__content">
    <p>
        <?= $model['content'] ?>
    </p>
    <span><?= $model['price'] ?> ₽</span>
</div>
<div class="feedback-card__actions">
    <a class="button__small-color response-button button"
       type="button">Подтвердить</a>
    <a class="button__small-color refusal-button button"
       type="button">Отказать</a>
</div>
