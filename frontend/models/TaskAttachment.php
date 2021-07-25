<?php

namespace frontend\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task_attachments".
 *
 * @property int $id
 * @property int $task_id
 * @property string $file_type
 * @property string $file_name
 * @property string $file_src
 *
 * @property Task $task
 */
class TaskAttachment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'task_attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['task_id', 'file_type', 'file_name', 'file_src'], 'required'],
            [['file_type', 'file_name', 'file_src'], 'safe'],
            [['task_id'], 'integer'],
            [['file_type'], 'string', 'max' => 32],
            [['file_name'], 'string', 'max' => 64],
            [['file_src'], 'string', 'max' => 256],
            [
                ['task_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Task::class,
                'targetAttribute' => ['task_id' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'file_type' => 'File Type',
            'file_name' => 'File Name',
            'file_src' => 'File Src',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
}
