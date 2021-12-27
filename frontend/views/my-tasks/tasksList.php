<?php
/**
 * @var Task $model;
 */

use Components\Categories\CategoryService;
use Components\Constants\UserConstants;
use Components\Routes\Route;
use frontend\models\Task;

?>


    <div class="new-task__title">
        <a href="tasks/view/<?= $model->id ?>" class="link-regular">
            <h2><?= $model->title ?></h2>
        </a>
        <a class="new-task__type link-regular" href="<?= Route::getTasks($model->category_id) ?>">
            <p><?= $model->category->title ?></p>
        </a>
    </div>
    <div class="task-status <?= $model->state ?>-status"><?= $model->state ?></div>
    <p class="new-task_description"><?= $model->description ?></p>
    <div class="feedback-card__top ">
        <a href="#"><img src="<?= $model->executor->avatar_src ?? UserConstants::USER_DEFAULT_AVATAR_SRC ?>" width="36" height="36"></a>
        <div class="feedback-card__top--name my-list__bottom">
            <p class="link-name"><a href="#" class="link-regular"><?= $model->executor->name ?></a></p>
            <a href="#" class="my-list__bottom-chat"></a>
            <?php for ($i = 0; $i < 5; $i++): ?>
                <span class="<?= ($model->executor->rating <= $i) ? 'star-disabled' : '' ?>"></span>
            <?php endfor; ?>
            <b><?= $model->executor->rating/100 ?></b>
        </div>
    </div>
