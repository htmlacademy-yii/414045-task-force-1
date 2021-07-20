<?php

use Components\Exceptions\CsvImportToSqlException;
use Components\Imports\CsvImportToSql;

require_once __DIR__ . '/vendor/autoload.php';


//Тестовый сценарий
try {
    $newFile = new CsvImportToSql('users.csv', './data/', 'users.sql', './');
    $newFile->import('taskforce', 'users');
} catch (CsvImportToSqlException $e) {
    print 'Ошибка: ' . $e->getMessage();
}
