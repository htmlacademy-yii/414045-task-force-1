<?php

namespace frontend\modules\api\controllers;

use frontend\models\Message;
use yii\rest\ActiveController;

class MessagesController extends ActiveController
{
    public $modelClass = Message::class;
}