<?php

use frontend\models\Task;
use yii\widgets\ActiveForm;

/**
 * @var Task $task;
 */

?>

<section class="modal form-modal refusal-form" id="refuse-form">
    <h2>Отказ от задания</h2>
    <p>
        <?php if ($task->customer_id === Yii::$app->user->id): ?>
        Вы собираетесь отменить задание.
        Вы уверены?
        <?php else: ?>
        Вы собираетесь отказаться от выполнения задания.
        Это действие приведёт к снижению вашего рейтинга.
        Вы уверены?
        <?php endif; ?>
    </p>
    <button class="button__form-modal button" id="close-modal"
            type="button">Отмена
    </button>
    <?php $action = $task->customer_id === Yii::$app->user->id ? '/cancel' : '/refuse' ?>
    <?php $form = ActiveForm::begin(['action' => Yii::$app->requestedParams['id'] . $action]) ?>
    <button class="button__form-modal refusal-button button"
            type="submit"><?= $task->customer_id === Yii::$app->user->id ? 'Отменить задание' : 'Отказаться' ?>
    </button>
    <?php $form::end() ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
