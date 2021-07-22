<?php

//Тестовый сценарий
use Components\Exceptions\CsvImportToSqlException;
use Components\Imports\CsvImportToSql;

require_once __DIR__ . '/vendor/autoload.php';

try {
    $prefix = '06_';
    $nameTable = 'reviews';
    $newFile = new CsvImportToSql('./data/', $nameTable . '.csv', './sqlExportData/', $nameTable . '.sql', $prefix);
    $newFile->import($nameTable);
} catch (CsvImportToSqlException $e) {
    print 'Ошибка: ' . $e->getMessage();
}