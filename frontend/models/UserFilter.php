<?php

declare(strict_types=1);

namespace frontend\models;

use yii\db\ActiveRecord;
use Components\Categories\CategoryService;

final class UserFilter extends ActiveRecord
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
        $this->categories = (new CategoryService())->getCategoryNames();
    }

    public function attributeLabels(): array
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

    public function rules(): array
    {
        return [
            [['showCategories', 'categories', 'isFree', 'isOnline', 'hasReview', 'isFavorites', 'userName'], 'safe'],
        ];
    }
}