<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\db\Exception;

class SeedController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionIndex()
    {
        $db = Yii::$app->db;
        if (!$db) {
            echo 'Нет подключения к БД';
            exit;
        }

        $sql = file_get_contents(__DIR__ . '/../../sqlExportData/01_categories.sql');

        $db->createCommand($sql)->execute();
        echo 'БД наполнена';
    }
}