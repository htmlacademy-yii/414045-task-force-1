<?php

/**
 * Проверка сгенерированной модели
 *
 * @deprecated
 */

namespace frontend\controllers;

use frontend\models\Category;
use yii\web\Controller;

class TestModelController extends Controller
{
    public function actionSave()
    {
        $category = new Category();
        $category->title = 'New category';
        $category->save();
    }

    public function actionShow()
    {
        $category = Category::find()->one();
        if ($category
        ) {
            return $this->render(
                'showCategory',
                ['categoryTitle' => $category->title]
            );
        }
    }
}
