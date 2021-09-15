<?php

/**
 * @var ActiveDataProvider $dataProvider ;
 * @var TaskFilter $taskFilter ;
 */

use frontend\models\TaskFilter;
use yii\data\ActiveDataProvider;
use yii\bootstrap\ActiveField;
use yii\bootstrap\ActiveForm;
use yii\widgets\ListView;

?>
<section class="new-task">
    <div class="new-task__wrapper">
        <h1>Новые задания</h1>
        <?php echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_taskList',
            'layout' => "{items}\n<div class='new-task__pagination'>{pager}</div>",
            'itemOptions' => [
                'tag' => 'label',
                'class' => 'new-task__card',
            ],
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
                ],
            ],
            'emptyText' => 'Новых задач нет',
            'emptyTextOptions' => [
                'tag' => 'p'
            ],

        ]);
        ?>
    </div>
</section>
<section class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'search-task__form',
            ],
        ]); ?>
        <fieldset class="search-task__categories">
            <legend>Категории</legend>
            <?= $form->field($taskFilter, 'showCategories',
                ['labelOptions' => ['label' => null]])->checkboxList($taskFilter->categories, [
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
            <?= $form->field($taskFilter, 'isNotExecutor', [
                'options' => ['class' => ''],
                'checkboxTemplate' => '<label class="checkbox__legend">{input}<span>{labelTitle}</span></label>',
            ])->checkbox(['class' => 'visually-hidden checkbox__input']) ?>
            <?= $form->field($taskFilter, 'isRemoteWork', [
                'options' => ['class' => ''],
                'checkboxTemplate' => '<label class="checkbox__legend">{input}<span>{labelTitle}</span></label>',
            ])->checkbox(['class' => 'visually-hidden checkbox__input']) ?>
        </fieldset>
        <?= $form->field($taskFilter, 'period',
            [
                'options' => ['class' => 'field-container'],
                'labelOptions' => ['class' => 'search-task__name']
            ])->dropDownList($taskFilter->periodLabels, ['class' => 'multiple-select input']); ?>
        <?= $form->field($taskFilter, 'taskName',
            [
                'options' => ['class' => 'field-container'],
                'labelOptions' => ['class' => 'search-task__name']
            ])->textInput(['class' => 'input-middle input']); ?>
        <button class="button" type="submit">Искать</button>
        <?php ActiveForm::end() ?>
    </div>
</section>