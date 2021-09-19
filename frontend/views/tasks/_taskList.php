<?php
/**
 * @var Task $model;
 */

use Components\Categories\CategoryHelper;
use Components\Routes\Route;
use frontend\models\Task;

?>

<div class="new-task__title">
    <a href="tasks/view/<?= $model->id ?>" class="link-regular">
        <h2><?= $model->title ?></h2></a>
    <a class="new-task__type link-regular" href="<?= Route::getTasks($model->category_id) ?>"><p>
            <?= $model->category->title ?> </p></a>
</div>
<div class="new-task__icon new-task__icon--<?= CategoryHelper::getCategoryName($model->category->title) ?>"></div>
<p class="new-task_description"> <?= $model->description ?> </p>
<b class="new-task__price new-task__price--translation"><?= $model->price ?>
    <b> ₽</b></b>
<p class="new-task__place"><?= $model->address ?></p>
<span class="new-task__time"><?= $model->getTimeDiff() ?></span>
