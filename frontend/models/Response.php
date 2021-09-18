<?php

namespace frontend\models;

use phpDocumentor\Reflection\Types\Self_;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "responses".
 *
 * @property int $id
 * @property int $task_id
 * @property int $user_id
 * @property string|null $content
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Task $task
 * @property User $user
 */
class Response extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'responses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['task_id', 'user_id'], 'required'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'datetime'],
            [
                ['task_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Task::class,
                'targetAttribute' => ['task_id' => 'id'],
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id'],
            ],
            [['content', 'created_at', 'updated_at'], 'safe'],
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
            'user_id' => 'UserHelper ID',
            'content' => 'Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

    /**
     * Gets query for [[UserHelper]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function getResponsesDataProvider($taskId): ActiveDataProvider
    {
        $query = (new Query())->select(['user_id', 'content', 'price', 'name', 'avatar_src', 'rating'])
            ->from('responses')
            ->where(['task_id' => $taskId])
            ->leftJoin(['u' => 'users'], 'u.id = responses.user_id');

        $query = self::find()->where(['task_id' => $taskId]);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
    }
}
