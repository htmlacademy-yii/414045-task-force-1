<?php

declare(strict_types=1);

namespace frontend\models;

use Components\Constants\TaskConstants;
use Components\Constants\UserConstants;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use frontend\models\Category;
use Components\Categories\CategoryHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property int|null $role
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $city_id
 * @property string|null $full_address
 * @property string|null $birthday
 * @property string|null $about
 * @property string|null $avatar_src
 * @property string|null $phone
 * @property string|null $skype
 * @property string|null $over_messenger
 * @property int|null $rating
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Message[] $messages
 * @property Portfolio[] $portfolios
 * @property Response[] $responses
 * @property Review[] $reviews
 * @property Task[] $tasks
 * @property UserSetting $userSetting
 * @property City $city
 * @property UsersSpecialty[] $usersSpecialties
 */
final class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }

    public static function getUserFilter(): UserFilter
    {
        $userFilter = new UserFilter();
        if (Yii::$app->request->getIsPost()) {
            $userFilter->load(Yii::$app->request->post());
        }

        return $userFilter;
    }

    public static function getDataProviderFilter(UserFilter $filter): ActiveDataProvider
    {
        $conditions = [
            'role' => UserConstants::USER_ROLE_EXECUTOR,
            's.category_id' => array_flip($filter->categories)
        ];
        $query = self::find()->leftJoin(['s' => 'users_specialty'],
            's.user_id = users.id')->where($conditions);

        if (!empty($filter->showCategories)) {
            $category = new CategoryHelper();
            $conditionCategoryId = ['category_id' => $category->categoriesFilter($filter->showCategories)];
            $query->filterWhere($conditionCategoryId);
        }

        if ($filter->isFree) {
            $conditionUserIsFree = ['!=', 'state', TaskConstants::NEW_TASK_STATUS_NAME];
            $query->leftJoin(['t' => 'tasks'], 't.executor_id = users.id')->andWhere($conditionUserIsFree);
        }

        if ($filter->isOnline) {
            //
        }

        if ($filter->hasReview) {
            $conditionsHasReview = 'addressee_id = users.id';
            $query->leftJoin('reviews', 'addressee_id = users.id')->andWhere($conditionsHasReview);
        }

        if ($filter->isFavorites) {
            //
        }

        if ($filter->userName) {
            $conditionName = ['like', 'name', $filter->userName];
            $query->andWhere($conditionName);
        }

        return new ActiveDataProvider([
            'query' => $query->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['rating', 'integer', 'min' => 0, 'max' => 500],
            ['role', 'in', 'range' => [0, 1]],
            ['email', 'unique', 'message' => 'Пользователь с таким email уже существует'],
            ['email', 'required', 'message' => 'Введите валидный адрес электронной почты'],
            ['email', 'email', 'message' => 'Введите валидный адрес электронной почты'],
            ['name', 'required', 'message' => 'Введите ваше имя и фамилию'],
            ['city_id', 'required', 'message' => 'Укажите город, чтобы находить подходящие задачи'],
            ['password', 'required', 'message' => 'Введите пароль'],
            ['password', 'string', 'min' => 8, 'tooShort' => 'Длина пароля от 8 символов'],
            ['about', 'string'],
            [
                ['name', 'password', 'avatar_src', 'skype', 'over_messenger'],
                'string',
                'max' => 128,
            ],
            ['email', 'string', 'max' => 64],
            ['full_address', 'string', 'max' => 256],
            ['phone', 'string', 'max' => 20],
            [['created_at', 'updated_at'], 'datetime'],
            [
                ['city_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => City::class,
                'targetAttribute' => ['city_id' => 'id'],
            ],
            [
                [
                    'name',
                    'email',
                    'city_id',
                    'full_address',
                    'birthday',
                    'about',
                    'avatar_src',
                    'phone',
                    'skype',
                    'over_messenger',
                    'rating',
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
            'name' => 'ваше имя',
            'email' => 'электронная почта',
            'password' => 'пароль',
            'city_id' => 'город проживания',
            'full_address' => 'полный адрес',
            'birthday' => 'день рождения',
            'about' => 'о себе',
            'phone' => 'телефон',
            'skype' => 'Skype',
            'over_messenger' => 'другой месседжер',
        ];
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return ActiveQuery
     */
    public function getMessages(): ActiveQuery
    {
        return $this->hasMany(Message::class, ['sender_id' => 'id']);
    }

    /**
     * Gets query for [[Portfolios]].
     *
     * @return ActiveQuery
     */
    public function getPortfolios(): ActiveQuery
    {
        return $this->hasMany(Portfolio::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return ActiveQuery
     */
    public function getResponses(): ActiveQuery
    {
        return $this->hasMany(Response::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return ActiveQuery
     */
    public function getReviews(): ActiveQuery
    {
        if ($this->role === UserConstants::USER_ROLE_CUSTOMER) {
            return $this->hasMany(Review::class, ['sender_id' => 'id']);
        }

        return $this->hasMany(Review::class, ['addressee_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return ActiveQuery
     */
    public function getTasks(): ActiveQuery
    {
        if ($this->role === UserConstants::USER_ROLE_CUSTOMER) {
            return $this->hasMany(Task::class, ['customer_id' => 'id']);
        }

        return $this->hasMany(Task::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[UserSetting]].
     *
     * @return ActiveQuery
     */
    public function getUserSetting(): ActiveQuery
    {
        return $this->hasOne(UserSetting::class, ['user_id' => 'id']);
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

    /**
     * Gets query for [[UsersSpecialties]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('users_specialty', ['user_id' => 'id']);
    }

    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}
