<?php

declare(strict_types=1);

namespace Components\Tasks;

use frontend\models\Task;

abstract class AbstractAction
{
    abstract public static function getActionNameForUser(Task $task);

    abstract public static function getActionName();

    abstract public static function authActionForUser(Task $task);
}