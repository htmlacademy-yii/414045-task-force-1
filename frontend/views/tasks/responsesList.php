<?php
/**
 * @var Response $model ;
 */

use Components\Constants\TaskConstants;
use Components\Constants\UserConstants;
use Components\Constants\ResponseConstants;
use Components\Routes\Route;
use Components\Users\UserHelper;
use frontend\models\Response;

$rating = UserHelper::getCountRatingStars($model->user->rating);
?>

    <div class="feedback-card__top">
        <a href="<?= Route::getUserView($model->user_id) ?>"><img
                    src="<?= $model->user->avatar_src ?? UserConstants::USER_DEFAULT_AVATAR_SRC ?>" width="55"
                    height="55"></a>
        <div class="feedback-card__top--name">
            <p><a href="<?= Route::getUserView($model->user_id) ?>" class="link-regular"><?= $model->user->name ?></a>
            </p>
            <?php for ($i = 0; $i < 5; $i++): ?>
                <span class="<?= ($rating <= $i) ? 'star-disabled' : '' ?>"></span>
            <?php endfor; ?>
            <b><?= $rating ?></b>
        </div>
        <span class="new-task__time">25 минут назад</span>
    </div>
    <div class="feedback-card__content">
        <p>
            <?= $model->content ?>
        </p>
        <span><?= $model->price ?> ₽</span>
    </div>
<?php if ($model->task->state === TaskConstants::NEW_TASK_STATUS_NAME && $model->state !== ResponseConstants::REFUSE_STATUS_NAME && Yii::$app->user->id === $model->task->customer_id): ?>
    <div class="feedback-card__actions">
        <a href="<?= Route::getTaskResponseAccept($model->task_id, $model->id) ?>"
           class="button__small-color response-button button"
           type="button">Подтвердить</a>
        <a href="<?= Route::getTaskResponseRefuse($model->task_id, $model->id) ?>"
           class="button__small-color refusal-button button"
           type="button">Отказать</a>
    </div>
<?php endif; ?>