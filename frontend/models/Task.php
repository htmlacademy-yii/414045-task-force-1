<?php

namespace frontend\models;

use Yii;

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
 * @property Response[] $responses
 * @property Review $review
 * @property TaskAttachment[] $taskAttachments
 * @property User $customer
 * @property User $executor
 * @property Category $category
 * @property City $city
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'title', 'category_id', 'state', 'price'], 'required'],
            [['customer_id', 'executor_id', 'category_id', 'price', 'city_id'], 'integer'],
            [['description'], 'string'],
            [['deadline', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 64],
            [['state'], 'string', 'max' => 10],
            [['attachment_src', 'address', 'address_comment'], 'string', 'max' => 256],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
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
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Review]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReview()
    {
        return $this->hasOne(Review::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[TaskAttachments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskAttachments()
    {
        return $this->hasMany(TaskAttachment::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }
}
