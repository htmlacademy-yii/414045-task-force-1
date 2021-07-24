<?php

namespace frontend\models;

use Yii;

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
class TaskAttachment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'file_type', 'file_name', 'file_src'], 'required'],
            [['task_id'], 'integer'],
            [['file_type'], 'string', 'max' => 32],
            [['file_name'], 'string', 'max' => 64],
            [['file_src'], 'string', 'max' => 256],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
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
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
}
