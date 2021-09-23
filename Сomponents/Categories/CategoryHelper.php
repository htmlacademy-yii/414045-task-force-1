<?php

declare(strict_types=1);

namespace Components\Categories;

use Components\Constants\CategoryConstants;
use frontend\models\Category;

final class CategoryHelper
{
    public static function getCategoryNames(): array
    {
        $categories = collect(Category::find()->select('title')->all());
        return $categories->pluck('title')->all();
    }

    public static function getCategoryName($nameCategoryForUser): string
    {
        $categoriesNames = array_flip(CategoryConstants::NAME_MAP);
        return $categoriesNames[$nameCategoryForUser];
    }

    public static function categoriesFilter($categoriesId): array
    {
        return array_map(fn($id): int => $id + 1, $categoriesId);
    }
}