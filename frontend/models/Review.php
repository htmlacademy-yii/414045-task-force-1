<?php

namespace frontend\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "reviews".
 *
 * @property int $id
 * @property int $sender_id
 * @property int $addressee_id
 * @property int $task_id
 * @property int $rating
 * @property string $content
 * @property string|null $created_at
 *
 * @property User $sender
 * @property User $addressee
 * @property Task $task
 */
class Review extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'reviews';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [
                ['sender_id', 'addressee_id', 'task_id', 'rating', 'content'],
                'required',
            ],
            [['sender_id', 'addressee_id', 'task_id'], 'integer'],
            [['rating'], 'integer', 'min' => 0, 'max' => 500],
            [['content'], 'string'],
            [['created_at'], 'safe'],
            [['task_id'], 'unique'],
            [
                ['sender_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['sender_id' => 'id'],
            ],
            [
                ['addressee_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['addressee_id' => 'id'],
            ],
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
            'sender_id' => 'Sender ID',
            'addressee_id' => 'Addressee ID',
            'task_id' => 'Task ID',
            'rating' => 'Rating',
            'content' => 'Content',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Sender]].
     *
     * @return ActiveQuery
     */
    public function getSender(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'sender_id']);
    }

    /**
     * Gets query for [[Addressee]].
     *
     * @return ActiveQuery
     */
    public function getAddressee(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'addressee_id']);
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