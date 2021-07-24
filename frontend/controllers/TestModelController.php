<?php

/**
 * Проверка сгенерированной модели
 *
 * @deprecated
 */
namespace frontend\controllers;

use yii\web\Controller;
use frontend\models\Category;

class TestModelController extends Controller
{
    public function actionSave(){
        $category = new Category();
        $category->title = 'New category';
        $category->save();
    }
    public function actionShow()
    {
        $category = Category::find()->one();
        if ($category){
            return $this->render('showCategory', ['categoryTitle' => $category->title]);
        }
    }
}
