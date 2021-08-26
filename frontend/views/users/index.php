<?php

/**
 * @var ActiveDataProvider $dataProvider ;
 * @var UserFilter $userFilter ;
 */

use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use frontend\models\UserFilter;
use yii\bootstrap\ActiveField;
use yii\bootstrap\ActiveForm;


echo ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_list',
    'options' => [
        'tag' => 'section',
        'class' => 'user__search'
    ],
    'itemOptions' => [
        'tag' => 'div',
        'class' => 'content-view__feedback-card user__search-wrapper'
    ],
    'layout' => "{items}\n{pager}",
    'pager' => [
        'maxButtonCount' => 5,
        'activePageCssClass' => 'pagination__item--current',
        'prevPageCssClass' => 'pagination__item',
        'nextPageCssClass' => 'pagination__item',
        'pageCssClass' => 'pagination__item',
        'prevPageLabel' => '',
        'nextPageLabel' => '',
        'options' => [
            'tag' => 'ul',
            'class' => 'new-task__pagination-list',
        ]
    ],
]); ?>
<section class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'search-task__form',
            ],
        ]); ?>
        <fieldset class="search-task__categories">
            <legend>Категории</legend>
            <?= $form->field($userFilter, 'showCategories',
                ['labelOptions' => ['label' => null]])->checkboxList($userFilter->categories, [
                'item' => function ($index, $label, $name, $checked, $value) {
                    $checked = $checked ? 'checked' : '';
                    return "<label class='checkbox__legend'>
                            <input type='checkbox' {$checked} name='{$name}' value='{$value}' tabindex='3'>
                            <span>{$label}</span>
                            </label>";
                }

            ]) ?>
        </fieldset>
        <fieldset class="search-task__categories">
            <legend>Дополнительно</legend>
            <?= $form->field($userFilter, 'isFree', [
                'options' => ['class' => ''],
                'checkboxTemplate' => '<label class="checkbox__legend">{input}<span>{labelTitle}</span></label>',
            ])->checkbox(['class' => 'visually-hidden checkbox__input']) ?>
            <?= $form->field($userFilter, 'isOnline', [
                'options' => ['class' => ''],
                'checkboxTemplate' => '<label class="checkbox__legend">{input}<span>{labelTitle}</span></label>',
            ])->checkbox(['class' => 'visually-hidden checkbox__input']) ?>
            <?= $form->field($userFilter, 'hasReview', [
                'options' => ['class' => ''],
                'checkboxTemplate' => '<label class="checkbox__legend">{input}<span>{labelTitle}</span></label>',
            ])->checkbox(['class' => 'visually-hidden checkbox__input']) ?>
            <?= $form->field($userFilter, 'isFavorites', [
                'options' => ['class' => ''],
                'checkboxTemplate' => '<label class="checkbox__legend">{input}<span>{labelTitle}</span></label>',
            ])->checkbox(['class' => 'visually-hidden checkbox__input']) ?>
        </fieldset>
        <?= $form->field($userFilter, 'userName',
            [
                'options' => ['class' => 'field-container'],
                'labelOptions' => ['class' => 'search-task__name']
            ])->textInput(['class' => 'input-middle input']); ?>
        <button class="button" type="submit">Искать</button>
        <?php ActiveForm::end() ?>
    </div>
</section>
