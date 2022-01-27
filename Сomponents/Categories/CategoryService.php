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
    /**
     * @return array
     */
    public function getCategoryNames(): array
    {
        $categories = collect(Category::find()->select('title')->all());
        return $categories->pluck('title')->all();
    }

    /**
     * @return array
     */
    public function getCategoryNamesForDB(): array
    {
        $categories = collect(Category::find()->select('title')->all());
        $categories = $categories->pluck('title')->all();
        array_unshift($categories, null);
        unset($categories[0]);

        return $categories;
    }

    /**
     * @param string $nameCategoryForUser
     * @return string
     */
    public function getCategoryName(string $nameCategoryForUser): string
    {
        $categoriesNames = array_flip(CategoryConstants::NAME_MAP);
        return $categoriesNames[$nameCategoryForUser];
    }

    /**
     * @param array|string $categoriesId
     * @return array
     */
    public function categoriesFilter(array|string $categoriesId): array
    {
        return array_map(fn($id): int => $id + 1, $categoriesId);
    }
}