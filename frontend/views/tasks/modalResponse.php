<?php

use yii\widgets\ActiveForm;
use frontend\models\Response;

/**
 * @var Response $response ;
 */

?>

<section class="modal response-form form-modal" id="response-form">
    <h2>Отклик на задание</h2>
    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->requestedParams['id'] . '/response',
    ]) ?>
    <?= $form->field($response, 'price', [
        'options' => [
            'tag' => 'p',
        ],
        'labelOptions' => [
            'class' => 'form-modal-description',
        ],
        'inputOptions' => [
            'class' => 'response-form-payment input input-middle input-money',
        ],
        'errorOptions' => [
            'tag' => 'span',
            'class' => 'registration__text-error',
        ],
    ])->input('text') ?>
    <?= $form->field($response, 'content', [
        'options' => [
            'tag' => 'p',
        ],
        'labelOptions' => [
            'class' => 'form-modal-description',
        ],
        'inputOptions' => [
            'class' => 'input textarea',
            'rows' => 4,
            'placeholder' => 'Place your text',
        ],
        'errorOptions' => [
            'tag' => 'span',
            'class' => 'registration__text-error',
        ],
    ])->textarea() ?>
    <button class="button modal-button" type="submit">Отправить</button>
    <?php $form::end() ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>