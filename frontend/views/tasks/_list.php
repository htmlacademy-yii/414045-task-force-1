<?php

?>

<div class="new-task__title">
    <a href="view.html" class="link-regular">
        <h2><?= $model->title ?></h2></a>
    <a class="new-task__type link-regular" href="#"><p>
            <?= $model->category->title ?> </p></a>
</div>
<div class="new-task__icon new-task__icon--translation"></div>
<p class="new-task_description"> <?= $model->description ?> </p>
<b class="new-task__price new-task__price--translation"><?= $model->price ?>
    <b> ₽</b></b>
<p class="new-task__place"><?= $model->address ?></p>
<span class="new-task__time"><?= $model->getTimeDiff() ?></span>
