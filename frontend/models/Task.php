<?php

declare(strict_types=1);

namespace frontend\models;

use Components\Constants\TaskConstants;
use Components\Time\TimeDifference;
use Exception;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Components\Categories\CategoryService;

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
 * @property string|null $address
 * @property string|null $location_point
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
            $category = new CategoryService();
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
            [['category_id'], 'default', 'value' => 1],
            [
                ['category_id'],
                'exist',
                'targetClass' => Category::class,
                'targetAttribute' => 'id',
                'message' => 'Выбранной категории не существует'
            ],
            [['price'], 'integer', 'min' => 0],
            [['deadline'], 'default', 'value' => null],
            [['deadline'], 'date', 'message' => 'Формат для ввода даты ""'],
            [['created_at', 'updated_at'], 'date', 'format' => 'yyyy-M-d H:m:s'],
            [['title', 'description'], 'trim'],
            [['title'], 'required', 'message' => 'Это поле не может быть пустым'],
            [
                'title',
                'string',
                'length' => [10, 64],
                'tooShort' => 'Должно содержать от 10 символов',
                'tooLong' => 'Должно содержать не более 64 символов'
            ],
            [['description'], 'string', 'min' => 30, 'tooShort' => 'Длина текста не может быть менее 30 символов'],
            [['state'], 'in', 'range' => TaskConstants::STATUS_MAP],
            [
                ['address', 'address_comment'],
                'string',
                'max' => 256,
            ],
            [['location_point'], 'string', 'max' => 64],
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
                [
                    'title',
                    'description',
                    'state',
                    'price',
                    'deadline',
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
            'address' => 'Локация',
            'address_comment' => 'Комментарий к адресу',
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
     * Gets query for [[CategoryService]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getMessages(): ActiveQuery
    {
        return $this->hasMany(Message::class, ['task_id' => 'id']);
    }

    public function fields()
    {
        return [
            'title',
            'published_at' => 'created_at',
            'new_messages' => function () {
                return count($this->messages);
            },
            'author_name' => function () {
                return $this->customer->name;
            },
            'id',
        ];
    }
}
