<?php

//Тестовый сценарий
use Components\Exceptions\CsvImportToSqlException;
use Components\Imports\CsvImportToSql;

require_once __DIR__ . '/vendor/autoload.php';

try {
    $nameTable = 'reviews';
    $newFile = new CsvImportToSql('./data/', $nameTable . '.csv', './sqlExportData/', $nameTable . '.sql');
    $newFile->import('taskforce', $nameTable);
} catch (CsvImportToSqlException $e) {
    print 'Ошибка: ' . $e->getMessage();
}