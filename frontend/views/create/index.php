<?php

use frontend\models\UploadTaskAttachmentsFiles;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use frontend\models\Task;
use frontend\assets\CreateTaskAsset;

/**
 * @var $task Task ;
 * @var $files UploadTaskAttachmentsFiles ;
 * @var $categories array ;
 */

CreateTaskAsset::register($this);
?>

<section class="create__task">
    <h1>Публикация нового задания</h1>
    <div class="create__task-main">
        <?php $form = ActiveForm::begin([
            'action' => '/create/check-form',
            'options' => [
                'enctype' => 'multipart/form-data',
                'class' => 'create__task-form form-create',
                'id' => 'task-form',
            ]
        ]); ?>
        <?= $form->field($task, 'title',
            [
                'options' => [
                    'class' => 'field-container',
                ],
                'inputOptions' => [
                    'class' => 'input textarea',
                    'placeholder' => 'повесить полку',
                ],
                'errorOptions' => [
                    'tag' => 'span',
                    'class' => 'registration__text-error',
                ],
            ])->input('text')->hint('Кратко опишите суть работы', ['tag' => 'span']); ?>
        <?= $form->field($task, 'description',
            [
                'options' => [
                    'class' => 'field-container',
                ],
                'inputOptions' => [
                    'class' => 'input textarea',
                    'rows' => 7,
                    'placeholder' => 'Place your text',
                ],
                'errorOptions' => [
                    'tag' => 'span',
                    'class' => 'registration__text-error',
                ],
            ])->textarea()->hint('Укажите все пожелания и детали, чтобы исполнителям было проще соориентироваться',
            ['tag' => 'span']); ?>
        <?= $form->field($task, 'category_id',
            [
                'options' => [
                    'class' => 'field-container',
                ],
                'inputOptions' => [
                    'class' => 'multiple-select input multiple-select-big',
                ],
                'errorOptions' => [
                    'tag' => 'span',
                    'class' => 'registration__text-error',
                ],
            ])->dropDownList($categories)->hint('Выберите категорию', ['tag' => 'span']) ?>
        <div class="field-container">
            <label>Файлы</label>
            <span>Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу</span>
            <div class="create__file">
                <span>Добавить новый файл</span>
            </div>
        </div>
        <?= $form->field($task, 'address',
            [
                'options' => [
                    'class' => 'field-container',
                ],
                'inputOptions' => [
                    'id' => 'autoComplete',
                    'class' => 'input-navigation input-middle input',
                    'placeholder' => 'Санкт-Петербург, Калининский район',
                ],
                'errorOptions' => [
                    'tag' => 'span',
                    'class' => 'registration__text-error',
                ],
            ])->input('search')->hint('Укажите адрес исполнения, если задание требует присутствия',
            ['tag' => 'span']); ?>
        <div class="create__price-time">
            <?= $form->field($task, 'price',
                [
                    'options' => [
                        'class' => 'field-container create__price-time--wrapper',
                    ],
                    'inputOptions' => [
                        'class' => 'input textarea input-money',
                        'placeholder' => '1000',
                    ],
                    'errorOptions' => [
                        'tag' => 'span',
                        'class' => 'registration__text-error',
                    ],
                ])->input('text')->hint('Не заполняйте для оценки исполнителем', ['tag' => 'span']); ?>
            <?= $form
                ->field($task, 'deadline',
                    [
                        'options' => [
                            'class' => 'field-container create__price-time--wrapper',
                        ],
                        'errorOptions' => [
                            'tag' => 'span',
                            'class' => 'registration__text-error',
                        ],
                    ])->widget(DatePicker::class,
                    [
                        'language' => 'ru',
                        'options' => [
                            'class' => 'input-middle input input-date',
                            'placeholder' => 'гггг-мм-дд',
                            'autocomplete' => 'off',
                        ]
                    ])->hint('Укажите крайний срок исполнения', ['tag' => 'span']); ?>
        </div>
        <?php $form::end(); ?>
        <div class="create__warnings">
            <div class="warning-item warning-item--advice">
                <h2>Правила хорошего описания</h2>
                <h3>Подробности</h3>
                <p>Друзья, не используйте случайный<br>
                    контент – ни наш, ни чей-либо еще. Заполняйте свои
                    макеты, вайрфреймы, мокапы и прототипы реальным
                    содержимым.</p>
                <h3>Файлы</h3>
                <p>Если загружаете фотографии объекта, то убедитесь,
                    что всё в фокусе, а фото показывает объект со всех
                    ракурсов.</p>
            </div>
            <?php if ($task->errors): ?>
                <div class="warning-item warning-item--error">
                    <h2>Ошибки заполнения формы</h2>
                    <?php foreach ($task->errors as $title => $errors): ?>
                        <h3><?= $task->getAttributeLabel($title) ?></h3>
                        <?php foreach ($errors as $error): ?>
                            <p><?= $error ?></p><br>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <button form="task-form" class="button" type="submit">Опубликовать</button>
</section>