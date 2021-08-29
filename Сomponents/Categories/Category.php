<?php

namespace Components\Categories;

class Category
{
    public static function getCategoryNames(): array
    {
        $categories = collect(\frontend\models\Category::find()->select('title')->all());
        return $categories->pluck('title')->all();
    }

    static function categoriesFilter($categoriesId): array
    {
        return array_map(fn($id): int => $id + 1, $categoriesId);
    }
}