<?php

use frontend\assets\AccountAsset;
use frontend\models\AccountSettingsForm;
use frontend\models\User;
use yii\widgets\ActiveForm;

/**
 * @var User $user
 * @var AccountSettingsForm $accountSettings
 */

AccountAsset::register($this);
?>

<section class="account__redaction-wrapper">
    <h1>Редактирование настроек профиля</h1>
    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
            'id' => 'AccountSettingsForm'
        ]
    ]); ?>
    <div class="account__redaction-section">
        <h3 class="div-line">Настройки аккаунта</h3>
        <div class="account__redaction-section-wrapper">
            <div class="account__redaction-avatar">
                <img src="<?= $user->avatar_src ?? './img/user.png' ?>" width="156" height="156">
                <?= $form->field($accountSettings, 'avatar', [
                    'options' => [
                        'tag' => null,
                    ],
                    'template' => '{input}{label}',
                    'labelOptions' => [
                        'class' => 'link-regular'
                    ]
                ])->fileInput(['hidden' => true]) ?>
            </div>
            <div class="account__redaction">
                <?= $form->field($accountSettings, 'name', [
                    'options' => ['class' => 'field-container account__input account__input--name'],
                ])->textInput(['class' => 'input textarea', 'placeholder' => $user->name, 'disabled' => true]); ?>
                <?= $form->field($accountSettings, 'email', [
                    'options' => ['class' => 'field-container account__input account__input--email'],
                ])->input('email', ['class' => 'input textarea', 'placeholder' => $user->email]); ?>
                <?= $form->field($accountSettings, 'address', [
                    'options' => ['class' => 'field-container account__input account__input--address'],
                ])->textInput(['class' => 'input textarea', 'placeholder' => $user->full_address]) ?>
                <?= $form->field($accountSettings, 'birthday', [
                    'options' => ['class' => 'field-container account__input account__input--date'],
                ])->input('date', ['class' => 'input-middle input input-date', 'placeholder' => $user->birthday]) ?>
                <?= $form->field($accountSettings, 'about', [
                    'options' => ['class' => 'field-container account__input account__input--info'],
                ])->textarea([
                    'class' => 'input textarea',
                    'rows' => 7,
                    'placeholder' => $user->about ?? 'Place your text'
                ]) ?>
            </div>
        </div>
        <h3 class="div-line">Выберите свои специализации</h3>
        <div class="account__redaction-section-wrapper">
            <?= $form->field($accountSettings, 'userSpecialties', [
                'options' => ['class' => 'search-task__categories account_checkbox--bottom'],
            ])->label(false)->checkboxList($accountSettings->specialties, [
                'tag' => null,
                'item' => function ($index, $label, $name, $checked, $value) {
                    $checked = $checked ? 'checked' : '';
                    return "<label class='checkbox__legend'>
                            <input class='visually-hidden checkbox__input' type='checkbox' {$checked} name='{$name}' value='{$value}' tabindex='3'>
                            <span>{$label}</span>
                            </label>";
                }
            ]) ?>
        </div>
        <h3 class="div-line">Безопасность</h3>
        <div class="account__redaction-section-wrapper account__redaction">
            <?= $form->field($accountSettings, 'password', [
                'options' => ['class' => 'field-container account__input'],
            ])->passwordInput(['class' => 'input textarea']) ?>
            <?= $form->field($accountSettings, 'confirmPassword', [
                'options' => ['class' => 'field-container account__input'],
            ])->passwordInput(['class' => 'input textarea']) ?>
        </div>

        <h3 class="div-line">Фото работ</h3>

        <div class="account__redaction-section-wrapper account__redaction">
            <span class="dropzone">Выбрать фотографии</span>
            <div class="user__card-photo">
                <?php foreach ($user->portfolios as $photo): ?>
                <a href="<?= $photo->img_src ?>">
                    <img src="<?= $photo->img_src ?>" width="85" height="86">
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <h3 class="div-line">Контакты</h3>
        <div class="account__redaction-section-wrapper account__redaction">
            <?= $form->field($accountSettings, 'phone', [
                'options' => ['class' => 'field-container account__input'],
            ])->textInput([
                'type' => 'tel',
                'class' => 'input textarea',
                'placeholder' => $user->phone
            ]) ?>
            <?= $form->field($accountSettings, 'skype', [
                'options' => ['class' => 'field-container account__input'],
            ])->textInput([
                'class' => 'input textarea',
                'placeholder' => $user->skype
            ]) ?>
            <?= $form->field($accountSettings, 'overMessenger', [
                'options' => ['class' => 'field-container account__input'],
            ])->textInput([
                'class' => 'input textarea',
                'placeholder' => $user->over_messenger
            ]) ?>
        </div>
        <h3 class="div-line">Настройки сайта</h3>
        <h4>Уведомления</h4>
        <div class="account__redaction-section-wrapper account_section--bottom">
            <div class="search-task__categories account_checkbox--bottom">
                <?= $form->field($accountSettings, 'isMessageNtfEnabled', [
                    'options' => [
                        'tag' => null,
                    ],
                    'template' => '<label class="checkbox__legend">{input}<span>Новое сообщение</span></label>'
                ])
                    ->checkbox([
                        'class' => 'visually-hidden checkbox__input',
                    ], false) ?>
                <?= $form->field($accountSettings, 'isActionNtfEnabled', [
                    'options' => [
                        'tag' => null,
                    ],
                    'template' => '<label class="checkbox__legend">{input}<span>Действия по заданию</span></label>'
                ])
                    ->checkbox([
                        'class' => 'visually-hidden checkbox__input'
                    ], false) ?>
                <?= $form->field($accountSettings, 'isNewReviewNtfEnabled', [
                    'options' => [
                        'tag' => null,
                    ],
                    'template' => '<label class="checkbox__legend">{input}<span>Новый отзыв</span></label>'
                ])
                    ->checkbox([
                        'class' => 'visually-hidden checkbox__input'
                    ], false) ?>
            </div>
            <div class="search-task__categories account_checkbox account_checkbox--secrecy">
                <?= $form->field($accountSettings, 'isActive', [
                    'options' => [
                        'tag' => null,
                    ],
                    'template' => '<label class="checkbox__legend">{input}<span>Показывать мои контакты только заказчику</span></label>'
                ])
                    ->checkbox([
                        'class' => 'visually-hidden checkbox__input'
                    ], false) ?>
                <?= $form->field($accountSettings, 'isHidden', [
                    'options' => [
                        'tag' => null,
                    ],
                    'template' => '<label class="checkbox__legend">{input}<span>Не показывать мой профиль</span></label>'
                ])
                    ->checkbox([
                        'class' => 'visually-hidden checkbox__input'
                    ], false) ?>
            </div>
        </div>
    </div>
    <button form="AccountSettingsForm" class="button" type="submit">Сохранить изменения</button>
    <?php ActiveForm::end(); ?>
