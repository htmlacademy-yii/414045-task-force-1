<?php

namespace frontend\models;

use Components\Constants\TaskConstants;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property int $customer_id
 * @property int|null $executor_id
 * @property string $title
 * @property string|null $description
 * @property int $category_id
 * @property string $state
 * @property int $price
 * @property string|null $deadline
 * @property string|null $attachment_src
 * @property int|null $city_id
 * @property string|null $address
 * @property string|null $address_comment
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Response $responses
 * @property Review $review
 * @property TaskAttachment $taskAttachments
 * @property User $customer
 * @property User $executor
 * @property Category $category
 * @property City $city
 */
class Task extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [
                ['customer_id', 'title', 'category_id', 'state', 'price'],
                'required',
            ],
            [['price'], 'integer', 'min' => 0],
            [['description'], 'string'],
            [['deadline', 'created_at', 'updated_at'], 'datetime'],
            [['title'], 'string', 'max' => 64],
            [['state'], 'in', TaskConstants::STATUS_MAP],
            [
                ['attachment_src', 'address', 'address_comment'],
                'string',
                'max' => 256,
            ],
            [
                ['customer_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['customer_id' => 'id'],
            ],
            [
                ['executor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['executor_id' => 'id'],
            ],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::class,
                'targetAttribute' => ['category_id' => 'id'],
            ],
            [
                ['city_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => City::class,
                'targetAttribute' => ['city_id' => 'id'],
            ],
            [
                [
                    'title',
                    'description',
                    'state',
                    'price',
                    'deadline',
                    'attachment_src',
                    'address',
                    'address_comment',
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
            'customer_id' => 'Customer ID',
            'executor_id' => 'Executor ID',
            'title' => 'Title',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'state' => 'State',
            'price' => 'Price',
            'deadline' => 'Deadline',
            'attachment_src' => 'Attachment Src',
            'city_id' => 'City ID',
            'address' => 'Address',
            'address_comment' => 'Address Comment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return ActiveQuery
     */
    public function getResponses(): ActiveQuery
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Review]].
     *
     * @return ActiveQuery
     */
    public function getReview(): ActiveQuery
    {
        return $this->hasOne(Review::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[TaskAttachments]].
     *
     * @return ActiveQuery
     */
    public function getTaskAttachments(): ActiveQuery
    {
        return $this->hasMany(TaskAttachment::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return ActiveQuery
     */
    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return ActiveQuery
     */
    public function getExecutor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }
}
