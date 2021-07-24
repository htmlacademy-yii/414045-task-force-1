<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "users_specialty".
 *
 * @property int $id
 * @property int $user_id
 * @property int $categoriy_id
 *
 * @property User $user
 * @property Category $categoriy
 */
class UsersSpecialty extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_specialty';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'categoriy_id'], 'required'],
            [['user_id', 'categoriy_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['categoriy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['categoriy_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'categoriy_id' => 'Categoriy ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Categoriy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriy()
    {
        return $this->hasOne(Category::class, ['id' => 'categoriy_id']);
    }
}
