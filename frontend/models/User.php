<?php

namespace frontend\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
class User extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['rating'], 'integer', 'min' => 0, 'max' => 500],
            [['role'], 'in', [0, 1]],
            [['name', 'email', 'password', 'city_id'], 'required'],
            [['about'], 'string'],
            [
                ['name', 'password', 'avatar_src', 'skype', 'over_messenger'],
                'string',
                'max' => 128,
            ],
            [['email'], 'string', 'max' => 64],
            [['email'], 'email'],
            [['full_address'], 'string', 'max' => 256],
            [['phone'], 'string', 'max' => 20],
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
            'id' => 'ID',
            'role' => 'Role',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'city_id' => 'City ID',
            'full_address' => 'Full Address',
            'birthday' => 'Birthday',
            'about' => 'About',
            'avatar_src' => 'Avatar Src',
            'phone' => 'Phone',
            'skype' => 'Skype',
            'over_messenger' => 'Over Messenger',
            'rating' => 'Rating',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
        return $this->hasMany(Review::class, ['sender_id' => 'id']);
    }


    /**
     * Gets query for [[Tasks]].
     *
     * @return ActiveQuery
     */
    public function getTasks(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id']);
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
     */
    public function getUsersSpecialties(): ActiveQuery
    {
        return $this->hasMany(UsersSpecialty::class, ['user_id' => 'id']);
    }
}
