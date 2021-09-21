<?php

use frontend\models\City;
use yii\helpers\Html;
use frontend\models\User;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;

/**
 * @var User $user ;
 */
?>

<section class="registration__user">
    <h1>Регистрация аккаунта</h1>
    <div class="registration-wrapper">
        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'registration__user-form form-create',
            ],
        ]); ?>
        <?= $form->field($user, 'email',
            [
                'options' => [
                    'class' => 'field-container field-container--registration',
                ],
                'inputOptions' => [
                    'class' => 'input textarea',
                    'placeholder' => 'kumarm@mail.ru',
                ],
                'errorOptions' => [
                    'tag' => 'span',
                    'class' => 'registration__text-error',
                ],
            ])->input('email') ?>
        <?= $form->field($user, 'name',
            [
                'options' => [
                    'class' => 'field-container field-container--registration',
                ],
                'inputOptions' => [
                    'class' => 'input textarea',
                    'placeholder' => 'Мамедов Кумар'
                ],
                'errorOptions' => [
                    'tag' => 'span',
                    'class' => 'registration__text-error',
                ],
            ]) ?>
        <?= $form->field($user, 'city_id',
            [
                'options' => [
                    'class' => 'field-container field-container--registration',
                ],
                'inputOptions' => [
                    'class' => 'multiple-select input town-select registration-town',
                ],
                'errorOptions' => [
                    'tag' => 'span',
                    'class' => 'registration__text-error',
                ],
            ])->dropDownList(
            City::find()->select(['title', 'id'])->indexBy('id')->column())
        ?>
        <?= $form->field($user, 'password',
            [
                'options' => [
                    'class' => 'field-container field-container--registration',
                ],
                'inputOptions' => [
                    'class' => 'input textarea'
                ],
                'errorOptions' => [
                    'tag' => 'span',
                    'class' => 'registration__text-error',
                ],
            ])->passwordInput() ?>
        <?= Html::submitButton('Создать аккаунт', ['class' => 'button button__registration']) ?>
        <?php $form::end(); ?>
    </div>
</section>
