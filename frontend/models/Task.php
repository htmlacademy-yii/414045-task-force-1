<?php

declare(strict_types=1);

namespace frontend\models;

use Components\Constants\TaskConstants;
use Components\Time\TimeDifference;
use Exception;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use frontend\models\Category;
use Components\Categories\CategoryHelper;

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
final class Task extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'tasks';
    }

    public static function getTaskDataProvider(TaskFilter $filter, int $page_size): ActiveDataProvider
    {
        $conditions['state'] = TaskConstants::NEW_TASK_STATUS_NAME;
        $query = self::find()->where($conditions);

        if (!empty($filter->showCategories)) {
            $category = new CategoryHelper();
            $conditionCategoryId = ['category_id' => $category->categoriesFilter($filter->showCategories)];
            $query->filterWhere($conditionCategoryId);
        }

        if ($filter->isNotExecutor) {
            $isNotExecutor = ['executor_id' => null];
            $query->andWhere($isNotExecutor);
        }

        if ($filter->isRemoteWork) {
            $conditionsIsRemoteWork = ['address' => null];
            $query->andWhere($conditionsIsRemoteWork);
        }

        if ($filter->period) {
            $conditionsPeriod = ['>', 'created_at', self::dateFilter($filter->period)];
            $query->andWhere($conditionsPeriod);
        }

        if ($filter->taskName) {
            $conditionsName = ['like', 'title', $filter->taskName];
            $query->andWhere($conditionsName);
        }

        return new ActiveDataProvider([
            'query' => $query->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => $page_size,
            ],
        ]);
    }

    private static function dateFilter($period): string|bool
    {
        return match ($period) {
            TaskFilter::PERIOD_DAY => date('Y-m-d H:i:s', strtotime('-1 day')),
            TaskFilter::PERIOD_WEEK => date('Y-m-d H:i:s', strtotime('-7 day')),
            TaskFilter::PERIOD_MONTH => date('Y-m-d H:i:s', strtotime('-1 month')),
            TaskFilter::PERIOD_ALL => false,
        };
    }

    /**
     * @throws Exception
     */
    public function getTimeDiff(): string
    {
        $timeDiff = new TimeDifference($this->created_at);

        return $timeDiff->getCountTimeUnits(
                ['day' => 'a', 'hour' => 'h', 'minute' => 'i']
            ) . ' назад';
    }



    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [
                ['customer_id', 'category_id', 'state', 'price'],
                'required',
            ],
            [['price'], 'integer', 'min' => 0],
            [['description'], 'string'],
            [['deadline', 'created_at', 'updated_at'], 'datetime'],
            [['title'], 'required', 'message' => 'Это поле не может быть пустым'],
            [['title'], 'string', 'max' => 64],
            [['state'], 'in', 'range' => TaskConstants::STATUS_MAP],
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
            'title' => 'Мне нужно',
            'description' => 'Подробности задания',
            'category_id' => 'Категория',
            'state' => 'Состояние',
            'price' => 'Бюджет',
            'deadline' => 'Сроки исполнения',
            'attachment_src' => 'Attachment Src',
            'city_id' => 'Город',
            'address' => 'Локация',
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
     * Gets query for [[CategoryHelper]].
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
