<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\db\Exception;
use yii\helpers\Console;
use yii\helpers\FileHelper;

class SeedController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionIndex()
    {
        $pathExportData = __DIR__ . '/../../sqlExportData';
        if (file_exists($pathExportData)) {
            if (is_dir($pathExportData)) {
                $files = FileHelper::findFiles($pathExportData, ['only' => ['*.sql']]);
                if (!$files) {
                    echo 'В указанной директории нет SQL файлов!';
                    exit;
                }

                foreach ($files as $file) {
                    echo $file . PHP_EOL;
                }
                if (Console::confirm('Подтвердите выбранные файлы')) {
                    $db = Yii::$app->db;

                    if (!$db) {
                        echo 'Нет подключения к БД';
                        exit;
                    }

                    foreach ($files as $file) {
                        $sql = file_get_contents($file);
                        $db->createCommand($sql)->execute();
                    }

                    echo 'БД наполнена';
                }

                exit;
            }
        }
    }
}