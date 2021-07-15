<?php

require_once __DIR__ . '/vendor/autoload.php';

// Для проверки автозагрузки классов
use Components\Exceptions\TaskStateException;
use Components\Tasks\Task;
$task = new Task(1, 1, 2);

try {
    var_dump($task->getPossibleActions('done'));
}
catch (TaskStateException $e) {
    echo $e->getMessage();
}
