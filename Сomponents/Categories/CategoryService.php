<?php

declare(strict_types=1);

namespace Components\Categories;

use Components\Constants\CategoryConstants;
use frontend\models\Category;

/**
 * Class CategoryService
 *
 * @package Components/Categories
 */
final class CategoryService
{
    public function getCategoryNames(): array
    {
        $categories = collect(Category::find()->select('title')->all());
        return $categories->pluck('title')->all();
    }

    public function getCategoryNamesForDB(): array
    {
        $categories = collect(Category::find()->select('title')->all());
        $categories = $categories->pluck('title')->all();
        array_unshift($categories, null);
        unset($categories[0]);

        return $categories;
    }

    public function getCategoryName($nameCategoryForUser): string
    {
        $categoriesNames = array_flip(CategoryConstants::NAME_MAP);
        return $categoriesNames[$nameCategoryForUser];
    }

    public function categoriesFilter($categoriesId): array
    {
        return array_map(fn($id): int => $id + 1, $categoriesId);
    }
}