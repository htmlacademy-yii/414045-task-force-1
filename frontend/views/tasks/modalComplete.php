<?php

use frontend\models\TaskCompleteForm;
use yii\widgets\ActiveForm;
use Components\Constants\TaskConstants;

/**
 * @var TaskCompleteForm $taskCompleteForm ;
 */

?>

<section class="modal completion-form form-modal" id="complete-form">
    <h2>Завершение задания</h2>
    <p class="form-modal-description">Задание выполнено?</p>
    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->requestedParams['id'] . '/complete',
    ]) ?>
    <?= $form->field($taskCompleteForm, 'completeState', [
        'template' => '{input}{label}',
        'options' => [
            'tag' => false,
        ],
        'errorOptions' => [
            'tag' => false,
        ],
        'labelOptions' => [
            'label' => TaskConstants::TASK_COMPLETE_FORM_STATE_SUCCESS_LABEL,
            'class' => 'completion-label completion-label--yes'
        ],
        'inputOptions' => [
            'value' => TaskConstants::TASK_COMPLETE_FORM_STATE_SUCCESS,
            'id' => 'completion-radio--yes',
            'class' => 'visually-hidden completion-input completion-input--yes'
        ],
    ])->input('radio') ?>
    <?= $form->field($taskCompleteForm, 'completeState', [
        'template' => '{input}{label}',
        'options' => [
            'tag' => false,
        ],
        'errorOptions' => [
            'tag' => false,
        ],
        'labelOptions' => [
            'label' => TaskConstants::TASK_COMPLETE_FORM_STATE_REFUSE_LABEL,
            'class' => 'completion-label completion-label--difficult'
        ],
        'inputOptions' => [
            'value' => TaskConstants::TASK_COMPLETE_FORM_STATE_REFUSE,
            'id' => 'completion-radio--yet',
            'class' => 'visually-hidden completion-input completion-input--difficult'
        ],
    ])->input('radio') ?>
    <?= $form->field($taskCompleteForm, 'comment', [
        'options' => [
            'tag' => 'p',
        ],
        'errorOptions' => [
            'tag' => 'span',
        ],
        'labelOptions' => [
            'class' => 'form-modal-description'
        ],
        'inputOptions' => [
            'class' => 'input textarea',
            'rows' => 4,
            'placeholder' => 'Place your text',
        ],
    ])->textarea() ?>
    <p class="form-modal-description">
        Оценка
    <div class="feedback-card__top--name completion-form-star">
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
    </div>
    </p>
    <?= $form->field($taskCompleteForm, 'rating', [
        'options' => [
            'tag' => false,
        ],
        'errorOptions' => [
            'tag' => false,
        ],
        'labelOptions' => [
            'label' => false,
        ],
        'inputOptions' => [
            'id' => 'rating',
        ],
    ])->input('hidden') ?>
    <button class="button modal-button" type="submit">Отправить</button>
    <?php $form::end() ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
