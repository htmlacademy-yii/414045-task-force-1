<?php

namespace frontend\models;

use yii\db\ActiveRecord;
use frontend\models\Category;

class TaskFilter extends ActiveRecord
{

    public const PERIOD_DAY = 'day';
    public const PERIOD_WEEK = 'week';
    public const PERIOD_MONTH = 'month';
    public const PERIOD_ALL = 'all';

    public array $categories = [];
    public array|string $showCategories = [];
    public bool $isNotExecutor = false;
    public bool $isRemoteWork = false;
    public string $period = '';
    public array $periodLabels = [
        self::PERIOD_DAY => 'За день',
        self::PERIOD_WEEK => 'За неделю',
        self::PERIOD_MONTH => 'За месяц',
        self::PERIOD_ALL => 'За всё время'
    ];
    public string $taskName = '';

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->categories = Category::find()->asArray()->select('title')->all();
        foreach ($this->categories as $key => $category) {
            $this->categories[$key] = $category['title'];
        }
    }

    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'isNotExecutor' => 'Без исполнителя',
            'isRemoteWork' => 'Удалённая работа',
            'period' => 'Период',
            'taskName' => 'Поиск по названию'
        ];
    }

    public function rules()
    {
        return [
            [['showCategories', 'categories', 'isNotExecutor', 'isRemoteWork', 'period', 'taskName'], 'safe'],
        ];
    }
}