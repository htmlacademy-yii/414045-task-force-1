<?php

require_once __DIR__ . '/vendor/autoload.php';

// Для проверки автозагрузки классов
use Components\Tasks\Task;
$task = new Task(1, 1, 2);


var_dump($task->getPossibleActions());