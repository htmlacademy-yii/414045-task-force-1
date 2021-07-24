<?php

namespace frontend\models;

use Yii;

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
 * @property Message[] $messages0
 * @property Portfolio[] $portfolios
 * @property Response[] $responses
 * @property Review[] $reviews
 * @property Review[] $reviews0
 * @property Task[] $tasks
 * @property Task[] $tasks0
 * @property UserSetting $userSetting
 * @property City $city
 * @property UsersSpecialty[] $usersSpecialties
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role', 'city_id', 'rating'], 'integer'],
            [['name', 'email', 'password', 'city_id'], 'required'],
            [['birthday', 'created_at', 'updated_at'], 'safe'],
            [['about'], 'string'],
            [['name', 'password', 'avatar_src', 'skype', 'over_messenger'], 'string', 'max' => 128],
            [['email'], 'string', 'max' => 64],
            [['full_address'], 'string', 'max' => 256],
            [['phone'], 'string', 'max' => 20],
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
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::class, ['sender_id' => 'id']);
    }

    /**
     * Gets query for [[Messages0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(Message::class, ['addressee_id' => 'id']);
    }

    /**
     * Gets query for [[Portfolios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPortfolios()
    {
        return $this->hasMany(Portfolio::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::class, ['sender_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews0()
    {
        return $this->hasMany(Review::class, ['addressee_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Task::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[UserSetting]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserSetting()
    {
        return $this->hasOne(UserSetting::class, ['user_id' => 'id']);
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

    /**
     * Gets query for [[UsersSpecialties]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsersSpecialties()
    {
        return $this->hasMany(UsersSpecialty::class, ['user_id' => 'id']);
    }
}
