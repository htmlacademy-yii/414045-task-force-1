<?php

declare(strict_types=1);

namespace frontend\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property int $sender_id
 * @property int $addressee_id
 * @property string $content
 * @property int $task_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property User $sender
 * @property User $addressee
 */
final class Message extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['sender_id', 'addressee_id', 'content', 'task_id'], 'required'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'datetime'],
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
            [
                [
                    'sender_id',
                    'addressee_id',
                    'content',
                    'task_id',
                    'created_at',
                    'updated_at',
                ],
                'safe',
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
            'content' => 'Content',
            'task_id' => 'Task ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

    public function fields()
    {
        return [
            'message' => 'content',
            'published_at' => 'created_at',
            'is_mine' => function () {
                return \Yii::$app->user->id === $this->sender_id;
            },
        ];
    }
}
