<?php

namespace frontend\models;

use yii\db\ActiveRecord;
use frontend\models\Category;

class UserFilter extends ActiveRecord
{

    public array $categories = [];
    public array|string $showCategories = [];
    public bool $isFree = false;
    public bool $isOnline = false;
    public bool $hasReview = false;
    public bool $isFavorites = false;

    public string $userName = '';

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
            'isFree' => 'Сейчас свободен',
            'isOnline' => 'Сейчас онлайн',
            'hasReview' => 'Есть отзывы',
            'isFavorites' => 'В избранном',
            'userName' => 'Поиск по имени'
        ];
    }

    public function rules()
    {
        return [
            [['showCategories', 'categories', 'isFree', 'isOnline', 'hasReview', 'isFavorites'], 'safe'],
        ];
    }
}