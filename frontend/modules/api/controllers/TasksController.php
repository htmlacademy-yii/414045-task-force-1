<?php

declare(strict_types=1);

namespace frontend\modules\api\controllers;

use frontend\models\Task;
use yii\rest\ActiveController;

class TasksController extends ActiveController
{
    public $modelClass = Task::class;
}