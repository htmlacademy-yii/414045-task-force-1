<?php
/**
 * @var Review $model ;
 */

use Components\Constants\UserConstants;
use Components\Routes\Route;
use frontend\models\Review;

?>

<p class="link-task link">Задание <a href="<?= Route::getTaskView($model->task_id) ?>" class="link-regular"><?= $model->task->title ?></a>
</p>
<div class="card__review">
    <a href="<?= Route::getUserView($model->sender->id) ?>"><img
                src="<?= $model->sender->avatar_src ?? UserConstants::USER_DEFAULT_AVATAR_SRC ?>" width="55"
                height="54"></a>
    <div class="feedback-card__reviews-content">
        <p class="link-name link"><a href="<?= Route::getUserView($model->sender->id) ?>"
                                     class="link-regular"><?= $model->sender->name ?></a></p>
        <p class="review-text"><?= $model->content ?></p>
    </div>
    <div class="card__review-rate">
        <p class="five-rate big-rate"><?= $model->rating ?><span></span></p>
    </div>
</div>
