<?php

namespace frontend\models;

use yii\base\Model;

class TaskCompleteForm extends Model
{
    public string $completeState = '';
    public string $comment = '';
    public ?int $rating = null;

    public function rules()
    {
        return [
            [['completeState', 'comment'], 'string'],
            [['rating'], 'match', 'pattern' => '/^[1-5]$/'],
            [['completeState', 'comment', 'rating'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'comment' => 'Комментарий',
        ];
    }
}