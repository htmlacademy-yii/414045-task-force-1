<?php

namespace Components\Categories;

use Components\Constants\CategoryConstants;

class Category
{
    public static function getCategoryNames(): array
    {
        $categories = collect(\frontend\models\Category::find()->select('title')->all());
        return $categories->pluck('title')->all();
    }

    public static function getCategoryName($nameCategoryForUser): string
    {
        $categoriesNames = array_flip(CategoryConstants::NAME_MAP);
        return $categoriesNames[$nameCategoryForUser];
    }

    static function categoriesFilter($categoriesId): array
    {
        return array_map(fn($id): int => $id + 1, $categoriesId);
    }
}