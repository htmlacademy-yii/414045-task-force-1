<?php

declare(strict_types=1);

namespace frontend\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "responses".
 *
 * @property int $id
 * @property int $task_id
 * @property int $user_id
 * @property string|null $content
 * @property int|null $price
 * @property string|null $state
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Task $task
 * @property User $user
 */
final class Response extends ActiveRecord
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
            [['created_at', 'updated_at'], 'date', 'format' => 'yyyy-M-d H:m:s'],
            [['price'], 'match', 'pattern' => '/^[0-9]*$/', 'message' => 'Введите целое положительное число'],
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
            [['content', 'price', 'state', 'created_at', 'updated_at'], 'safe'],
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
            'user_id' => 'User ID',
            'content' => 'Комментарий',
            'price' => 'Ваша цена',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[TaskService]].
     *
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    /**
     * Gets query for [[UserService]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function getResponsesDataProvider($taskId, $executorId = null): ActiveDataProvider
    {
        $query = self::find()->where(['task_id' => $taskId]);
        if ($executorId !== null) {
            $query->andWhere(['user_id' => $executorId]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
    }
}
